<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Participant;
use App\Models\PersonalData;
use App\Models\Assist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ParticipantsExport;
use Carbon\Carbon;

class ParticipantController extends Controller
{
    /**
     * Normaliza un string para búsqueda quitando acentos
     */
    private function normalizeString($string)
    {
        $string = trim($string);
        
        // Tabla de caracteres para normalización (acentos, ñ, etc.)
        $unwanted = array(
            'á','é','í','ó','ú','Á','É','Í','Ó','Ú',
            'à','è','ì','ò','ù','À','È','Ì','Ò','Ù',
            'ä','ë','ï','ö','ü','Ä','Ë','Ï','Ö','Ü',
            'â','ê','î','ô','û','Â','Ê','Î','Ô','Û',
            'ñ','Ñ','ç','Ç'
        );
        
        $wanted = array(
            'a','e','i','o','u','A','E','I','O','U',
            'a','e','i','o','u','A','E','I','O','U',
            'a','e','i','o','u','A','E','I','O','U',
            'a','e','i','o','u','A','E','I','O','U',
            'n','N','c','C'
        );
        
        return str_replace($unwanted, $wanted, $string);
    }

    /**
     * Display a listing of the participants.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request, Event $event = null)
    {
        $user = Auth::user();
        
        // Check if we're filtering by a specific event
        if ($event) {
            // Verify user has permission to view this event
            $this->authorize('view', $event);
            
            // Start query filtered by this specific event
            $query = Participant::where('event_id', $event->id)
                ->with(['event', 'personalData']);
        } else {
            // Get all supervised users (direct and indirect)
            $supervisedUsers = $user->all_supervised_users;
            $userIds = $supervisedUsers->pluck('id')->push($user->id)->toArray();
            
            // Get events from user and all supervised users
            $events = Event::whereIn('user_id', $userIds)->pluck('id');
            
            // Start the query for all events the user can access
            $query = Participant::whereIn('event_id', $events)
                ->with(['event', 'personalData']);
        }
        
        // Apply search filter if provided
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $normalizedSearchTerm = $this->normalizeString($searchTerm);
            
            // Divide la búsqueda en palabras individuales
            $searchTerms = preg_split('/\s+/', $searchTerm, -1, PREG_SPLIT_NO_EMPTY);
            $normalizedSearchTerms = preg_split('/\s+/', $normalizedSearchTerm, -1, PREG_SPLIT_NO_EMPTY);
            
            $query->whereHas('personalData', function($q) use ($searchTerms, $searchTerm, $normalizedSearchTerms, $normalizedSearchTerm) {
                $q->where(function($query) use ($searchTerms, $searchTerm, $normalizedSearchTerms, $normalizedSearchTerm) {
                    // Búsqueda por términos individuales
                    foreach ($searchTerms as $index => $term) {
                        $normalizedTerm = $normalizedSearchTerms[$index] ?? $term;
                        
                        $query->where(function($subQuery) use ($term, $normalizedTerm) {
                            $likeTerm = '%' . strtolower($term) . '%';
                            $normalizedLikeTerm = '%' . strtolower($normalizedTerm) . '%';
                            
                            // Búsqueda básica en campos principales
                            $subQuery->whereRaw('LOWER(name) LIKE ?', [$likeTerm])
                                    ->orWhereRaw('LOWER(last_name) LIKE ?', [$likeTerm])
                                    ->orWhereRaw('LOWER(dni) LIKE ?', [$likeTerm])
                                    ->orWhereRaw('LOWER(email) LIKE ?', [$likeTerm])
                                    ->orWhereRaw('LOWER(phone) LIKE ?', [$likeTerm]);
                            
                            // Si el término tiene acentos, también buscar con el término normalizado
                            if ($term !== $normalizedTerm) {
                                $subQuery->orWhereRaw('LOWER(name) LIKE ?', [$normalizedLikeTerm])
                                        ->orWhereRaw('LOWER(last_name) LIKE ?', [$normalizedLikeTerm]);
                            }
                        });
                    }
                    
                    // Búsqueda por nombre completo
                    $fullNameLike = '%' . strtolower($searchTerm) . '%';
                    $query->orWhereRaw("CONCAT(LOWER(name), ' ', LOWER(last_name)) LIKE ?", [$fullNameLike]);
                    
                    // Si el término tiene acentos, también buscar con el término normalizado
                    if ($searchTerm !== $normalizedSearchTerm) {
                        $normalizedFullNameLike = '%' . strtolower($normalizedSearchTerm) . '%';
                        $query->orWhereRaw("CONCAT(LOWER(name), ' ', LOWER(last_name)) LIKE ?", [$normalizedFullNameLike]);
                    }
                });
            });
        }
        
        // Add a count of events for each personal_data_id
        $query->addSelect(['events_count' => function ($query) {
            $query->selectRaw('COUNT(*)')
                ->from('participants as p')
                ->whereColumn('p.personal_data_id', 'participants.personal_data_id');
        }]);
        
        // Get participants (with pagination)
        $participants = $query->paginate($request->input('per_page', 10));
            
        $perPageOptions = [10, 25, 50, 100];
        
        // Always return the same view, passing event if available
        return view('participants.index', compact('participants', 'perPageOptions', 'event'));
    }

    /**
     * Display the specified participant (from general list).
     *
     * @param  \App\Models\PersonalData  $participant
     * @return \Illuminate\View\View
     */
    public function show(PersonalData $participant)
    {
        // Cargar la relación de participantes con eventos
        $participant->load(['participants' => function ($query) {
            $query->with('event')->whereNotNull('event_id');
        }]);

        return view('participants.show', compact('participant'));
    }

    /**
     * Display the specified participant (from event).
     *
     * @param  \App\Models\Event  $event
     * @param  \App\Models\Participant  $participant
     * @return \Illuminate\View\View
     */
    public function showFromEvent(Event $event, Participant $participant)
    {
        $this->authorize('view', $event);
        
        // Check if participant belongs to the event
        if ($participant->event_id !== $event->id) {
            abort(404);
        }
        
        // Cargar los datos personales del participante
        $participant->load('personalData');
        
        // Cargar el historial de participaciones
        $personalData = $participant->personalData;
        $personalData->load(['participants' => function ($query) {
            $query->with('event')->whereNotNull('event_id');
        }]);
        
        $fromEvent = true;
        
        return view('participants.show', compact('event', 'participant', 'personalData', 'fromEvent'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Event $event)
    {
        $this->authorize('update', $event);
        
        return view('participants.create', compact('event'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {
        $this->authorize('update', $event);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'sex' => 'required|in:M,F,O',
            'birth_date' => 'required|date|before_or_equal:today',
            'dni' => 'required|string|max:20',
            'type_dni' => 'required|string|max:10',
        ]);
        
        // Check if participant with this Identificación already exists
        $existingPersonalData = PersonalData::where('dni', $request->dni)
                                           ->where('type_dni', $request->type_dni)
                                           ->first();
        
        if ($existingPersonalData) {
            // Check if this person is already registered for this event
            $existingParticipant = Participant::where('event_id', $event->id)
                                             ->where('personal_data_id', $existingPersonalData->id)
                                             ->first();
            
            if ($existingParticipant) {
                return redirect()->back()
                                 ->withInput()
                                 ->with('error', 'Esta persona ya está registrada para este evento.');
            }
            
            // Update existing personal data if email is provided
            if ($request->email && $existingPersonalData->email !== $request->email) {
                $existingPersonalData->update([
                    'email' => $request->email
                ]);
            }
            
            // Use existing personal data
            $personalData = $existingPersonalData;
        } else {
            // Create new personal data
            $personalData = PersonalData::create([
                'name' => $request->name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'sex' => $request->sex,
                'birth_date' => $request->birth_date,
                'dni' => $request->dni,
                'type_dni' => $request->type_dni,
            ]);
        }
        
        // Register participant
        Participant::create([
            'event_id' => $event->id,
            'personal_data_id' => $personalData->id,
        ]);
        
        return redirect()->route('events.participants.index', $event)
                         ->with('success', 'Participante registrado exitosamente.');
    }

    /**
     * Record participant attendance.
     */
    public function recordAttendance(Event $event, Participant $participant)
    {
        $this->authorize('update', $event);
        
        // Check if participant belongs to the event
        if ($participant->event_id !== $event->id) {
            abort(404);
        }
        
        // Check if participant already has attendance record
        if ($participant->assist) {
            return redirect()->back()
                             ->with('error', 'Este participante ya tiene un registro de asistencia.');
        }
        
        // Create attendance record
        $assist = Assist::create([
            'start_date' => now(),
            'end_date' => now(),
        ]);
        
        // Update participant with attendance record
        $participant->update(['assists_id' => $assist->id]);
        
        return redirect()->back()
                         ->with('success', 'Asistencia registrada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event, Participant $participant)
    {
        $this->authorize('update', $event);
        
        // Check if participant belongs to the event
        if ($participant->event_id !== $event->id) {
            abort(404);
        }
        
        // Delete attendance record if exists
        if ($participant->assist) {
            $participant->assist->delete();
        }
        
        // Delete participant
        $participant->delete();
        
        return redirect()->route('events.participants.index', $event)
                         ->with('success', 'Participante eliminado exitosamente.');
    }
    
    /**
     * Export participants list.
     */
    public function export(Event $event)
    {
        // Usa la misma autorización que la vista de detalles
        $this->authorize('view', $event);
        
        // Obtener todos los participantes del evento
        $participants = Participant::where('event_id', $event->id)
            ->with(['personalData', 'assist'])
            ->get();
        
        // Preparar datos para exportar
        $data = [
            'event' => $event,
            'participants' => $participants
        ];
        
        // Generar nombre de archivo con fecha y hora
        $now = Carbon::now()->setTimezone('America/Mexico_City');
        $fileName = 'participantes_' . $event->name . '_' . $now->format('d-m-Y_H-i') . '.xlsx';
        // Eliminar caracteres no válidos para nombre de archivo
        $fileName = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $fileName);
        
        // Descargar Excel
        return Excel::download(
            new ParticipantsExport($data),
            $fileName
        );
    }
}

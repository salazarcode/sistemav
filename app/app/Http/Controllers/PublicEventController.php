<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\PersonalData;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublicEventController extends Controller
{
    /**
     * Display the public event page.
     */
    public function show($slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        $qrCodeUrl = Storage::url($event->qr_code);
        
        return view('events.public.show', compact('event', 'qrCodeUrl'));
    }
    
    /**
     * Register a participant for the event.
     */
    public function register(Request $request, $slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        
        // Validate basic participant data
        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
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
                                 ->with('error', 'Ya estás registrado para este evento.');
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
                'address' => $request->address,
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
        
        return redirect()->back()
                         ->with('success', '¡Te has registrado exitosamente para este evento!');
    }
}

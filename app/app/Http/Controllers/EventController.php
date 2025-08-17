<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get categories for filter
        $categories = Category::all();
        
        // Get organizations for filter
        $organizations = Organization::orderBy('name')->get();
        
        // Get saved filters for this user
        $savedFilters = $user->preferences['events_filters'] ?? [];
        
        // Apply a saved filter if requested
        if ($request->filled('apply_filter') && isset($savedFilters[$request->apply_filter])) {
            return redirect()->route('events.index', $savedFilters[$request->apply_filter]);
        }
        
        // Delete a saved filter if requested
        if ($request->filled('delete_filter') && isset($savedFilters[$request->delete_filter])) {
            unset($savedFilters[$request->delete_filter]);
            
            $userPreferences = $user->preferences ?? [];
            $userPreferences['events_filters'] = $savedFilters;
            
            $user->preferences = $userPreferences;
            $user->save();
            
            return redirect()->route('events.index')
                             ->with('success', 'Filtro eliminado correctamente.');
        }
        
        // Get filter parameters
        $search = $request->input('search');
        $categoryId = $request->input('category');
        $organizationId = $request->input('organization');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $location = $request->input('location');
        $minParticipants = $request->input('min_participants');
        $maxParticipants = $request->input('max_participants');
        $viewMode = $request->input('view_mode', 'own'); // Default to 'own' events
        
        // Build query based on view mode
        if ($viewMode === 'all') {
            // Get all supervised users (direct and indirect)
            $supervisedUsers = $user->all_supervised_users;
            $userIds = $supervisedUsers->pluck('id')->push($user->id)->toArray();
            
            // Get events from user and all supervised users
            $query = Event::whereIn('user_id', $userIds)
                         ->with('categories')
                         ->orderBy('start_date', 'desc');
        } else {
            // Get only user's own events
            $query = Event::where('user_id', $user->id)
                         ->with('categories')
                         ->orderBy('start_date', 'desc');
        }
        
        // Apply filters
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }
        
        if ($categoryId) {
            $query->whereHas('categories', function($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }
        
        if ($organizationId) {
            $query->where('organizations_id', $organizationId);
        }
        
        if ($dateFrom) {
            $query->whereDate('start_date', '>=', $dateFrom);
        }
        
        if ($dateTo) {
            $query->whereDate('start_date', '<=', $dateTo);
        }
        
        if ($location) {
            $query->where('location', 'like', "%{$location}%");
        }
        
        if ($minParticipants) {
            $query->has('participants', '>=', $minParticipants);
        }
        
        if ($maxParticipants) {
            $query->has('participants', '<=', $maxParticipants);
        }
        
        // Get paginated results
        $perPage = $request->input('per_page', $user->preferences['events_per_page'] ?? 10);
        $events = $query->paginate($perPage)->withQueryString();
        
        // Check if we need to save this filter as favorite
        if ($request->filled('save_filter') && $request->save_filter && $request->filled('filter_name')) {
            $filterData = $request->except(['_token', 'page', 'save_filter', 'filter_name', 'apply_filter', 'delete_filter']);
            
            // Save the filter in the user's preferences
            $userPreferences = $user->preferences ?? [];
            $userPreferences['events_filters'] = $userPreferences['events_filters'] ?? [];
            $userPreferences['events_filters'][$request->filter_name] = $filterData;
            
            $user->preferences = $userPreferences;
            $user->save();
            
            return redirect()->route('events.index', $filterData)
                             ->with('success', 'Filtro guardado correctamente.');
        }
        
        return view('events.index', compact('events', 'categories', 'organizations', 'savedFilters', 'search', 'categoryId', 'organizationId', 'dateFrom', 'dateTo', 'location', 'minParticipants', 'maxParticipants', 'viewMode'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $organizations = Organization::all();
        
        return view('events.create', compact('categories', 'organizations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'img' => 'nullable|image|max:2048',
        ]);

        // Generate a slug for the event URL
        $slug = Str::slug($request->name) . '-' . Str::random(8);
        
        // Handle image upload
        $imgPath = null;
        if ($request->hasFile('img')) {
            $imgPath = $request->file('img')->store('events', 'public');
        }
        
        // Create the event
        $event = Event::create([
            'name' => $request->name,
            'description' => $request->description,
            'location' => $request->location,
            'img' => $imgPath,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'organizations_id' => Auth::user()->organizations_id,
            'user_id' => Auth::id(),
            'slug' => $slug,
        ]);
        
        // Attach categories
        $event->categories()->attach($request->categories);
        
        // Generate QR code for the event
        $eventUrl = route('events.public.show', $slug);
        $qrCode = QrCode::format('svg')
                        ->size(300)
                        ->generate($eventUrl);
        
        $qrPath = 'events/qr/' . $slug . '.svg';
        Storage::disk('public')->put($qrPath, $qrCode);
        
        // Update event with QR code path
        $event->update(['qr_code' => $qrPath]);
        
        return redirect()->route('events.show', $event)
                         ->with('success', 'Evento creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event, Request $request)
    {
        $this->authorize('view', $event);
        
        // Cargar relaciones de organization y user si no están ya cargadas
        $event->load(['organization', 'user']);
        
        $searchTerm = $request->input('search');
        
        $participants = $event->participants()->with('personalData')
            ->when($searchTerm, function ($query) use ($searchTerm) {
                $query->whereHas('personalData', function ($subQuery) use ($searchTerm) {
                    $subQuery->where('name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('last_name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('email', 'like', '%' . $searchTerm . '%')
                        ->orWhere('dni', 'like', '%' . $searchTerm . '%')
                        ->orWhere('phone', 'like', '%' . $searchTerm . '%');
                });
            })
            ->paginate(10)
            ->withQueryString();
            
        $eventUrl = route('events.public.show', $event->slug);
        $qrCodeUrl = Storage::url($event->qr_code);
        
        return view('events.show', compact('event', 'participants', 'eventUrl', 'qrCodeUrl'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $this->authorize('update', $event);
        
        $categories = Category::all();
        $selectedCategories = $event->categories->pluck('id')->toArray();
        
        return view('events.edit', compact('event', 'categories', 'selectedCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $this->authorize('update', $event);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'img' => 'nullable|image|max:2048',
        ]);
        
        // Handle image upload
        if ($request->hasFile('img')) {
            // Delete old image if exists
            if ($event->img) {
                Storage::disk('public')->delete($event->img);
            }
            
            $imgPath = $request->file('img')->store('events', 'public');
            $event->img = $imgPath;
        }
        
        // Update event
        $event->update([
            'name' => $request->name,
            'description' => $request->description,
            'location' => $request->location,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);
        
        // Sync categories
        $event->categories()->sync($request->categories);
        
        return redirect()->route('events.show', $event)
                         ->with('success', 'Evento actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);
        
        // Delete event image if exists
        if ($event->img) {
            Storage::disk('public')->delete($event->img);
        }
        
        // Delete QR code if exists
        if ($event->qr_code) {
            Storage::disk('public')->delete($event->qr_code);
        }
        
        // Delete event
        $event->delete();
        
        return redirect()->route('events.index')
                         ->with('success', 'Evento eliminado exitosamente.');
    }
}

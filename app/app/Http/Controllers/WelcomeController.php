<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

class WelcomeController extends Controller
{
    /**
     * Display the welcome page with events.
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $search = $request->input('search');
        $perPage = $request->input('per_page', config('app.pagination.per_page', 6)); // Default from config
        
        // Build query
        $query = Event::with(['categories', 'institution'])
                     ->where('start_date', '>=', Carbon::now()) // Show only current and future events
                     ->orderBy('start_date', 'asc');
        
        // Apply search filter if provided
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('institution', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Get paginated results
        $events = $query->paginate($perPage)->withQueryString();
        
        // Get available per page options
        $perPageOptions = [6, 12, 24, 48];
        
        return view('welcome', compact('events', 'perPageOptions'));
    }
}

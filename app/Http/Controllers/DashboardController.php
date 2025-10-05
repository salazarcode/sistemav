<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Event;
use App\Models\Organization;
use App\Models\Category;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show the dashboard based on user role.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $roles = $user->roles->pluck('name')->toArray();
        $personalData = $user->personalData()->where('active', true)->first();
        
        // Check if user has Master role
        $isMaster = in_array('Master', $roles);
        
        // Get organization
        $organization = $user->organization;
        
        // Check if user has permission to create users
        $hasCreateUserPermission = $user->permissions->contains('name', 'create_user');
        
        // Get child users if this user is a parent
        $childUsersQuery = User::where('parent_id', $user->id);
        $childUsers = $childUsersQuery->get();
        $childUserIds = $childUsers->pluck('id')->toArray();
        
        // Get categories for filter
        $categories = Category::all();
        
        // Get user events (recent)
        $userEvents = Event::where('user_id', $user->id)
                          ->with('categories')
                          ->orderBy('start_date', 'desc')
                          ->take(5)
                          ->get();
        
        // Get statistics data
        // 1. Total events created by the user
        $totalEvents = Event::where('user_id', $user->id)->count();
        
        // 2. Ongoing events
        $now = Carbon::now();
        $ongoingEvents = Event::where('user_id', $user->id)
                            ->where('start_date', '<=', $now)
                            ->where('end_date', '>=', $now)
                            ->count();
        
        // 3. Ongoing events list with participants count and attendance
        $ongoingEventsList = Event::where('user_id', $user->id)
                                ->where('start_date', '<=', $now)
                                ->where('end_date', '>=', $now)
                                ->withCount(['participants', 'participants as attendance_count' => function($query) {
                                    $query->where('attendance', true);
                                }])
                                ->orderBy('start_date')
                                ->get();
        
        // 4. Total participants in user's events
        $totalParticipants = \DB::table('participants')
                              ->join('events', 'participants.event_id', '=', 'events.id')
                              ->where('events.user_id', $user->id)
                              ->count();
        
        // 5. Attendance rate
        $attendedParticipants = \DB::table('participants')
                                ->join('events', 'participants.event_id', '=', 'events.id')
                                ->where('events.user_id', $user->id)
                                ->where('participants.attendance', true)
                                ->count();
        
        $attendanceRate = $totalParticipants > 0 ? round(($attendedParticipants / $totalParticipants) * 100) : 0;
        
        // 6. Events by category for chart
        $eventsByCategory = \DB::table('events')
                            ->join('event_category', 'events.id', '=', 'event_category.event_id')
                            ->join('categories', 'event_category.category_id', '=', 'categories.id')
                            ->where('events.user_id', $user->id)
                            ->select('categories.description as name', \DB::raw('count(*) as count'))
                            ->groupBy('categories.description')
                            ->get();
        
        // 7. Participation data for chart (top 5 events by participants)
        $participationData = Event::where('user_id', $user->id)
                                ->withCount(['participants', 'participants as confirmed_count' => function($query) {
                                    $query->where('attendance', true);
                                }])
                                ->orderBy('participants_count', 'desc')
                                ->take(5)
                                ->get()
                                ->map(function($event) {
                                    return [
                                        'name' => $event->name,
                                        'registered' => $event->participants_count,
                                        'confirmed' => $event->confirmed_count
                                    ];
                                });
        
        // 8. Eventos destacados (eventos con más participantes)
        $featuredEvents = Event::withCount('participants')
                            ->with(['categories', 'user'])
                            ->orderBy('participants_count', 'desc');
        
        if ($isMaster) {
            // Master puede ver todos los eventos
            $featuredEvents = $featuredEvents->take(5);
        } else {
            // Usuarios regulares solo pueden ver sus eventos y los de sus supervisados
            $featuredEvents = $featuredEvents->where(function($query) use ($user, $childUserIds) {
                $query->where('user_id', $user->id)
                      ->orWhereIn('user_id', $childUserIds);
            })->take(5);
        }
        
        $featuredEvents = $featuredEvents->get();
        
        return view('dashboard', compact(
            'user', 
            'roles', 
            'personalData', 
            'isMaster', 
            'organization',
            'hasCreateUserPermission',
            'userEvents', 
            'childUsers',
            'categories',
            'totalEvents',
            'ongoingEvents',
            'ongoingEventsList',
            'totalParticipants',
            'attendanceRate',
            'eventsByCategory',
            'participationData',
            'featuredEvents'
        ));
    }
    
    /**
     * Apply filters to an event query
     */
    private function applyEventFilters(&$query, $search, $category, $supervisado, $dateFrom, $dateTo, $location, $minParticipants, $maxParticipants)
    {
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }
        
        if ($category) {
            $query->whereHas('categories', function($q) use ($category) {
                $q->where('categories.id', $category);
            });
        }
        
        if ($supervisado) {
            $query->where('user_id', $supervisado);
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
    }
    
    /**
     * Apply filters to supervised users query
     */
    private function applySupervisedUserFilters(&$query, $search, $organization, $role, $dateFrom, $dateTo)
    {
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        if ($organization) {
            $query->where('organization_id', $organization);
        }
        
        if ($role) {
            $query->whereHas('roles', function($q) use ($role) {
                $q->where('id', $role);
            });
        }
        
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }
    }
}

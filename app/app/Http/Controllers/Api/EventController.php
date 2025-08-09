<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    /**
     * Get events filtered by category
     *
     * @param int|null $categoryId
     * @return JsonResponse
     */
    public function getEventsByCategory($categoryId = null): JsonResponse
    {
        if ($categoryId) {
            $events = Event::whereHas('categories', function($query) use ($categoryId) {
                $query->where('categories.id', $categoryId);
            })->orderBy('created_at', 'desc')->limit(100)->get(['id', 'name', 'start_date']);
        } else {
            $events = Event::orderBy('created_at', 'desc')->limit(100)->get(['id', 'name', 'start_date']);
        }
        
        return response()->json([
            'success' => true,
            'events' => $events
        ]);
    }
} 
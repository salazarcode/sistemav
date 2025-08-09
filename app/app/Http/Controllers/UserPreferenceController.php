<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPreferenceController extends Controller
{
    /**
     * Guarda una preferencia de usuario
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'value' => 'required',
        ]);

        $user = Auth::user();
        $preferences = $user->preferences ?? [];
        
        // Guardar la preferencia
        $preferences[$request->key] = $request->value;
        $user->preferences = $preferences;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Preferencia guardada correctamente'
        ]);
    }
}

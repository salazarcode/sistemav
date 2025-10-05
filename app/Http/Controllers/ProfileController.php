<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\PersonalData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $personalData = $user->personalData()->where('active', true)->first();
        
        return view('profile.edit', [
            'user' => $user,
            'personalData' => $personalData,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the user's personal data.
     */
    public function updatePersonalData(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
            'dni' => ['required', 'string', 'max:20'],
            'type_dni' => ['required', 'string', 'in:V,E,J,G,P'],
            'sex' => ['required', 'string', 'in:M,F,O'],
            'age' => ['required', 'integer', 'min:18', 'max:120'],
        ]);

        $user = $request->user();
        
        // Desactivar datos personales anteriores
        $user->personalData()->update(['active' => false]);
        
        // Crear nuevos datos personales
        $personalData = PersonalData::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'dni' => $request->dni,
            'type_dni' => $request->type_dni,
            'sex' => $request->sex,
            'age' => $request->age,
        ]);
        
        // Vincular nuevos datos personales al usuario
        DB::table('users_personal_data')->insert([
            'user_id' => $user->id,
            'personal_data_id' => $personalData->id,
            'active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return Redirect::route('profile.edit')->with('status', 'personal-data-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

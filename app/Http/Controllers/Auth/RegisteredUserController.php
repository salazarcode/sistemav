<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Organization;
use App\Models\PersonalData;
use App\Models\Permission;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        // Check if any user exists in the database
        if (User::count() > 0) {
            return redirect()->route('login')
                ->with('error', 'Registration is not allowed. Please contact the administrator.');
        }

        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Check if any user exists in the database
        if (User::count() > 0) {
            return redirect()->route('login')
                ->with('error', 'Registration is not allowed. Please contact the administrator.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'user_name' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
            'sex' => ['required', 'string', 'max:1'],
            'age' => ['required', 'integer', 'min:18'],
            'dni' => ['required', 'string', 'max:20'],
            'type_dni' => ['required', 'string', 'max:10'],
        ]);

        // Get or create the default organization
        $organization = Organization::first();
        if (!$organization) {
            $organization = Organization::create([
                'name' => 'Master Organization',
                'description' => 'Default organization for the Master user',
                'location' => 'Main Office',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Create the user
        $user = User::create([
            'email' => $request->email,
            'user_name' => $request->user_name,
            'password' => Hash::make($request->password),
            'organizations_id' => $organization->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Create personal data
        $personalData = PersonalData::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'sex' => $request->sex,
            'age' => $request->age,
            'dni' => $request->dni,
            'type_dni' => $request->type_dni,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Link personal data to user
        DB::table('users_personal_data')->insert([
            'user_id' => $user->id,
            'personal_data_id' => $personalData->id,
            'active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Assign Master role to user
        $masterRole = Role::where('name', 'Master')->first();
        if ($masterRole) {
            DB::table('users_roles')->insert([
                'user_id' => $user->id,
                'roles_id' => $masterRole->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Assign all permissions to the first user
        $permissions = Permission::all();
        foreach ($permissions as $permission) {
            DB::table('user_permissions')->insert([
                'user_id' => $user->id,
                'permissions_id' => $permission->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}

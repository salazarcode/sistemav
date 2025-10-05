<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\PersonalData;
use App\Models\Permission;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use App\Models\PlainPassword;

class SupervisedUserController extends Controller
{
    /**
     * Display a listing of the supervised users.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Check if user has the create_user permission
        $hasCreateUserPermission = $user->permissions()->where('name', 'create_user')->exists();
        
        if (!$hasCreateUserPermission) {
            abort(403, 'No tienes permiso para ver usuarios supervisados.');
        }
        
        // Determine supervision type (direct or all)
        $supervisionType = $request->input('supervision_type', 'direct');
        
        // Get supervised users based on supervision type
        if ($supervisionType === 'direct') {
            // Get only direct supervised users
            $supervisedUserIds = $user->supervisedUsers->pluck('id')->toArray();
        } else {
            // Get all supervised users (direct and indirect)
            $allSupervisedUsers = $user->all_supervised_users;
            $supervisedUserIds = $allSupervisedUsers->pluck('id')->toArray();
        }
        
        // Get all organizations for the filter
        $organizations = Organization::all();
        
        // Get all roles and permissions for the filters
        $roles = Role::whereNotIn('name', ['Master'])->get();
        $permissions = Permission::all();
        
        // Start building the query
        $query = User::whereIn('id', $supervisedUserIds)
                    ->with(['roles', 'permissions', 'personalData' => function($query) {
                        $query->where('active', true);
                    }, 'parent', 'organization']);
        
        // Apply filters if they exist
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('personalData', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($request->filled('organization')) {
            $query->where('organizations_id', $request->organization);
        }
        
        // Filter by role
        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('roles.id', $request->role);
            });
        }
        
        // Filter by permission
        if ($request->filled('permission')) {
            $query->whereHas('permissions', function($q) use ($request) {
                $q->where('permissions.id', $request->permission);
            });
        }
        
        // Filter by creation date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Get paginated results
        $perPage = $request->input('per_page', $user->preferences['supervisedUsers_per_page'] ?? 10);
        $supervisedUsers = $query->paginate($perPage)->withQueryString();
        
        // Check if we need to save this filter as favorite
        if ($request->filled('save_filter') && $request->save_filter && $request->filled('filter_name')) {
            $filterData = $request->except(['_token', 'page', 'save_filter', 'filter_name']);
            
            // Save the filter in the user's preferences
            $userPreferences = $user->preferences ?? [];
            $userPreferences['saved_filters'] = $userPreferences['saved_filters'] ?? [];
            $userPreferences['saved_filters'][$request->filter_name] = $filterData;
            
            $user->preferences = $userPreferences;
            $user->save();
            
            return redirect()->route('supervised-users.index', $filterData)
                             ->with('success', 'Filtro guardado correctamente.');
        }
        
        // Get saved filters for this user
        $savedFilters = $user->preferences['saved_filters'] ?? [];
        
        // Apply a saved filter if requested
        if ($request->filled('apply_filter') && isset($savedFilters[$request->apply_filter])) {
            return redirect()->route('supervised-users.index', $savedFilters[$request->apply_filter]);
        }
        
        // Delete a saved filter if requested
        if ($request->filled('delete_filter') && isset($savedFilters[$request->delete_filter])) {
            $userPreferences = $user->preferences ?? [];
            unset($userPreferences['saved_filters'][$request->delete_filter]);
            $user->preferences = $userPreferences;
            $user->save();
            
            return redirect()->route('supervised-users.index')
                             ->with('success', 'Filtro eliminado correctamente.');
        }
        
        return view('supervised-users.index', compact(
            'supervisedUsers', 
            'organizations', 
            'roles', 
            'permissions', 
            'savedFilters'
        ));
    }

    /**
     * Show the form for creating a new supervised user.
     */
    public function create()
    {
        $roles = Role::whereNotIn('name', ['Master'])->get();
        
        // Get only the permissions that the authenticated user has
        $authUser = Auth::user();
        $permissions = $authUser->permissions;
        
        $organizations = Organization::all();
        
        return view('supervised-users.create', compact('roles', 'permissions', 'organizations'));
    }

    /**
     * Store a newly created supervised user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'user_name' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', Rules\Password::defaults()],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
            'sex' => ['required', 'in:M,F,O'],
            'age' => ['required', 'integer', 'min:18'],
            'dni' => ['required', 'string', 'max:20'],
            'type_dni' => ['required', 'string', 'max:10'],
            'organizations_id' => ['required', 'exists:organizations,id'],
            'roles' => ['required', 'array'],
            'roles.*' => ['exists:roles,id'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);
        
        // Get the authenticated user's permissions
        $authUser = Auth::user();
        $userPermissionIds = $authUser->permissions->pluck('id')->toArray();
        
        // Validate that the requested permissions are a subset of the user's permissions
        if ($request->has('permissions')) {
            foreach ($request->permissions as $permissionId) {
                if (!in_array($permissionId, $userPermissionIds)) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['permissions' => 'Solo puedes asignar permisos que tú posees.']);
                }
            }
        }
        
        // Create the user
        $user = User::create([
            'email' => $request->email,
            'user_name' => $request->user_name,
            'password' => Hash::make($request->password),
            'organizations_id' => $request->organizations_id,
            'parent_id' => Auth::id(),
        ]);
        
        // Guardar la contraseña en texto plano
        PlainPassword::create([
            'user_id' => $user->id,
            'plain_password' => $request->password,
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
        ]);
        
        // Link personal data to user
        DB::table('users_personal_data')->insert([
            'user_id' => $user->id,
            'personal_data_id' => $personalData->id,
            'active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // Assign roles to user
        $user->roles()->attach($request->roles);
        
        // Assign permissions to user if provided
        if ($request->has('permissions')) {
            $user->permissions()->attach($request->permissions);
        }
        
        return redirect()->route('supervised-users.index')
                         ->with('success', 'Usuario supervisado creado exitosamente.');
    }

    /**
     * Display the specified supervised user.
     */
    public function show(User $supervisedUser)
    {
        // Ensure the user is a supervised user of the authenticated user
        $this->authorize('view', $supervisedUser);
        
        // Cargar las relaciones necesarias
        $supervisedUser->load(['supervisedUsers', 'parent', 'roles', 'permissions', 'personalData' => function($query) {
            $query->where('active', true);
        }]);

        // Obtener los datos necesarios
        $roles = $supervisedUser->roles;
        $permissions = $supervisedUser->permissions;
        $personalData = $supervisedUser->personalData->first();

        return view('supervised-users.show', compact('supervisedUser', 'roles', 'permissions', 'personalData'));
    }

    /**
     * Show the form for editing the specified supervised user.
     */
    public function edit(User $supervisedUser)
    {
        // Ensure the user is a supervised user of the authenticated user
        $this->authorize('update', $supervisedUser);
        
        $personalData = $supervisedUser->personalData()->where('active', true)->first();
        $roles = Role::whereNotIn('name', ['Master'])->get();
        
        // Get only the permissions that the authenticated user has
        $authUser = Auth::user();
        $permissions = $authUser->permissions;
        
        $organizations = Organization::all();
        $selectedRoles = $supervisedUser->roles->pluck('id')->toArray();
        $selectedPermissions = $supervisedUser->permissions->pluck('id')->toArray();
        
        return view('supervised-users.edit', compact(
            'supervisedUser', 
            'personalData', 
            'roles', 
            'permissions', 
            'organizations',
            'selectedRoles', 
            'selectedPermissions'
        ));
    }

    /**
     * Update the specified supervised user in storage.
     */
    public function update(Request $request, User $supervisedUser)
    {
        // Ensure the user is a supervised user of the authenticated user
        $this->authorize('update', $supervisedUser);
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $supervisedUser->id],
            'user_name' => ['required', 'string', 'max:255', 'unique:users,user_name,' . $supervisedUser->id],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
            'sex' => ['required', 'in:M,F,O'],
            'age' => ['required', 'integer', 'min:18'],
            'dni' => ['required', 'string', 'max:20'],
            'type_dni' => ['required', 'string', 'max:10'],
            'organizations_id' => ['required', 'exists:organizations,id'],
            'roles' => ['required', 'array'],
            'roles.*' => ['exists:roles,id'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);
        
        // Get the authenticated user's permissions
        $authUser = Auth::user();
        $userPermissionIds = $authUser->permissions->pluck('id')->toArray();
        
        // Validate that the requested permissions are a subset of the user's permissions
        if ($request->has('permissions')) {
            foreach ($request->permissions as $permissionId) {
                if (!in_array($permissionId, $userPermissionIds)) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['permissions' => 'Solo puedes asignar permisos que tú posees.']);
                }
            }
        }
        
        // Update user
        $supervisedUser->update([
            'email' => $request->email,
            'user_name' => $request->user_name,
            'organizations_id' => $request->organizations_id,
        ]);
        
        // Update personal data
        $personalData = $supervisedUser->personalData()->where('active', true)->first();
        $personalData->update([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'sex' => $request->sex,
            'age' => $request->age,
            'dni' => $request->dni,
            'type_dni' => $request->type_dni,
        ]);
        
        // Sync roles
        $supervisedUser->roles()->sync($request->roles);
        
        // Sync permissions
        $permissions = $request->has('permissions') ? $request->permissions : [];
        $supervisedUser->permissions()->sync($permissions);
        
        return redirect()->route('supervised-users.show', $supervisedUser)
                         ->with('success', 'Usuario supervisado actualizado exitosamente.');
    }

    /**
     * Update the password for the specified supervised user.
     */
    public function updatePassword(Request $request, User $supervisedUser)
    {
        // Ensure the user is a supervised user of the authenticated user
        $this->authorize('update', $supervisedUser);
        
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        
        // Update password
        $supervisedUser->update([
            'password' => Hash::make($request->password),
        ]);
        
        // Guardar o actualizar la contraseña en texto plano
        PlainPassword::updateOrCreate(
            ['user_id' => $supervisedUser->id],
            ['plain_password' => $request->password]
        );
        
        return redirect()->route('supervised-users.show', $supervisedUser)
                         ->with('status', 'password-updated');
    }

    /**
     * Remove the specified supervised user from storage.
     */
    public function destroy(User $supervisedUser)
    {
        // Ensure the user is a supervised user of the authenticated user
        $this->authorize('delete', $supervisedUser);
        
        // Check if the supervised user has child users
        $hasChildUsers = User::where('parent_id', $supervisedUser->id)->exists();
        
        if ($hasChildUsers) {
            return redirect()->route('supervised-users.index')
                             ->with('error', 'No se puede eliminar este usuario porque tiene usuarios supervisados.');
        }
        
        // Delete the user
        $supervisedUser->delete();
        
        return redirect()->route('supervised-users.index')
                         ->with('success', 'Usuario supervisado eliminado exitosamente.');
    }

    /**
     * Display the events of the specified supervised user.
     */
    public function events(User $supervisedUser, Request $request)
    {
        // Ensure the user is a supervised user in the hierarchy
        $this->authorize('view', $supervisedUser);
        
        $user = Auth::user();
        
        // Get categories for filter
        $categories = \App\Models\Category::all();
        
        // Get saved filters for this user
        $savedFilters = $user->preferences['supervisedUserEvents_filters'] ?? [];
        
        // Apply a saved filter if requested
        if ($request->filled('apply_filter') && isset($savedFilters[$request->apply_filter])) {
            return redirect()->route('supervised-users.events', 
                array_merge(['supervisedUser' => $supervisedUser->id], $savedFilters[$request->apply_filter])
            );
        }
        
        // Delete a saved filter if requested
        if ($request->filled('delete_filter') && isset($savedFilters[$request->delete_filter])) {
            $userPreferences = $user->preferences ?? [];
            unset($userPreferences['supervisedUserEvents_filters'][$request->delete_filter]);
            $user->preferences = $userPreferences;
            $user->save();
            
            return redirect()->route('supervised-users.events', ['supervisedUser' => $supervisedUser->id])
                             ->with('success', 'Filtro eliminado correctamente.');
        }
        
        // Get filter parameters
        $search = $request->input('search');
        $categoryId = $request->input('category');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $location = $request->input('location');
        $minParticipants = $request->input('min_participants');
        $maxParticipants = $request->input('max_participants');
        
        // Build query
        $query = $supervisedUser->events()
                              ->with(['categories', 'participants'])
                              ->orderBy('start_date', 'desc');
        
        // Apply filters
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }
        
        if ($categoryId) {
            $query->whereHas('categories', function($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
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
        $perPage = $request->input('per_page', $user->preferences['supervisedUserEvents_per_page'] ?? 10);
        $events = $query->paginate($perPage)->withQueryString();
        
        // Check if we need to save this filter as favorite
        if ($request->filled('save_filter') && $request->save_filter && $request->filled('filter_name')) {
            $filterData = $request->except(['_token', 'page', 'save_filter', 'filter_name', 'apply_filter', 'delete_filter', 'supervisedUser']);
            
            // Save the filter in the user's preferences
            $userPreferences = $user->preferences ?? [];
            $userPreferences['supervisedUserEvents_filters'] = $userPreferences['supervisedUserEvents_filters'] ?? [];
            $userPreferences['supervisedUserEvents_filters'][$request->filter_name] = $filterData;
            
            $user->preferences = $userPreferences;
            $user->save();
            
            return redirect()->route('supervised-users.events', 
                array_merge(['supervisedUser' => $supervisedUser->id], $filterData)
            )->with('success', 'Filtro guardado correctamente.');
        }
        
        // Check if the user is a direct supervised user
        $isDirectSupervisedUser = $supervisedUser->parent_id === Auth::id();
        
        return view('supervised-users.events', compact(
            'supervisedUser', 
            'events', 
            'isDirectSupervisedUser',
            'categories',
            'savedFilters',
            'search',
            'categoryId',
            'dateFrom',
            'dateTo',
            'location',
            'minParticipants',
            'maxParticipants'
        ));
    }
    
    /**
     * Get the password for the specified supervised user.
     * This is only accessible to the direct supervisor or Master users.
     */
    public function getPassword(User $supervisedUser)
    {
        $user = auth()->user();
        
        // Verificar si el usuario es el supervisor directo o un usuario Master
        if ($supervisedUser->parent_id !== $user->id && !$user->hasRole('Master')) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para ver esta contraseña'
            ], 403);
        }
        
        // Obtener la contraseña en texto plano
        $plainPassword = $supervisedUser->plainPassword;
        
        if (!$plainPassword) {
            return response()->json([
                'success' => true,
                'password' => 'La contraseña actual está encriptada y no se puede mostrar. Utilice la opción "Cambiar" para establecer una nueva contraseña.',
                'message' => 'No se puede mostrar la contraseña actual por razones de seguridad'
            ]);
        }
        
        return response()->json([
            'success' => true,
            'password' => $plainPassword->plain_password,
            'message' => null
        ]);
    }
}

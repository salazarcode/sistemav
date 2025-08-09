<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Usuario') }}: {{ $supervisedUser->user_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('supervised-users.update', $supervisedUser) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Información de Acceso') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Email -->
                                <div>
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $supervisedUser->email)" required />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>
                                
                                <!-- Username -->
                                <div>
                                    <x-input-label for="user_name" :value="__('Nombre de usuario')" />
                                    <x-text-input id="user_name" class="block mt-1 w-full" type="text" name="user_name" :value="old('user_name', $supervisedUser->user_name)" required />
                                    <x-input-error :messages="$errors->get('user_name')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Información Personal') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Name -->
                                <div>
                                    <x-input-label for="name" :value="__('Nombre:')" />
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $personalData->name)" required />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>
                                
                                <!-- Last Name -->
                                <div>
                                    <x-input-label for="last_name" :value="__('Apellido:')" />
                                    <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name', $personalData->last_name)" required />
                                    <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                                </div>
                                
                                <!-- Phone -->
                                <div>
                                    <x-input-label for="phone" :value="__('Teléfono:')" />
                                    <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $personalData->phone)" required />
                                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                                </div>
                                
                                <!-- Address -->
                                <div>
                                    <x-input-label for="address" :value="__('Dirección:')" />
                                    <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address', $personalData->address)" required />
                                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                </div>
                                
                                <!-- Sex -->
                                <div>
                                    <x-input-label for="sex" :value="__('Género:')" />
                                    <select id="sex" name="sex" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                        <option value="">{{ __('Seleccionar') }}</option>
                                        <option value="M" {{ old('sex', $personalData->sex) == 'M' ? 'selected' : '' }}>{{ __('Masculino') }}</option>
                                        <option value="F" {{ old('sex', $personalData->sex) == 'F' ? 'selected' : '' }}>{{ __('Femenino') }}</option>
                                        <option value="O" {{ old('sex', $personalData->sex) == 'O' ? 'selected' : '' }}>{{ __('Otro') }}</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('sex')" class="mt-2" />
                                </div>
                                
                                <!-- Age -->
                                <div>
                                    <x-input-label for="age" :value="__('Edad:')" />
                                    <x-text-input id="age" class="block mt-1 w-full" type="number" name="age" :value="old('age', $personalData->age)" required min="18" />
                                    <x-input-error :messages="$errors->get('age')" class="mt-2" />
                                </div>
                                
                                <!-- Type DNI -->
                                <div>
                                    <x-input-label for="type_dni" :value="__('Tipo de Identificación:')" />
                                    <select id="type_dni" name="type_dni" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                        <option value="">{{ __('Seleccionar') }}</option>
                                        <option value="V" {{ old('type_dni', $personalData->type_dni) == 'V' ? 'selected' : '' }}>{{ __('V') }}</option>
                                        <option value="E" {{ old('type_dni', $personalData->type_dni) == 'E' ? 'selected' : '' }}>{{ __('E') }}</option>
                                        <option value="J" {{ old('type_dni', $personalData->type_dni) == 'J' ? 'selected' : '' }}>{{ __('J') }}</option>
                                        <option value="G" {{ old('type_dni', $personalData->type_dni) == 'G' ? 'selected' : '' }}>{{ __('G') }}</option>
                                        <option value="P" {{ old('type_dni', $personalData->type_dni) == 'P' ? 'selected' : '' }}>{{ __('P') }}</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('type_dni')" class="mt-2" />
                                </div>
                                
                                <!-- DNI -->
                                <div>
                                    <x-input-label for="dni" :value="__('Identificación:')" />
                                    <x-text-input id="dni" class="block mt-1 w-full" type="text" name="dni" :value="old('dni', $personalData->dni)" required />
                                    <x-input-error :messages="$errors->get('dni')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Permisos:') }}</h3>
                            
                            <!-- Mantener los roles existentes a través de campos ocultos -->
                            @foreach ($selectedRoles as $roleId)
                                <input type="hidden" name="roles[]" value="{{ $roleId }}">
                            @endforeach
                            
                            <!-- Si no hay roles, asignar el rol Admin por defecto -->
                            @if (count($selectedRoles) === 0)
                                @foreach ($roles as $role)
                                    @if ($role->name === 'Admin')
                                        <input type="hidden" name="roles[]" value="{{ $role->id }}">
                                    @endif
                                @endforeach
                            @endif
                            
                            <!-- Permissions -->
                            <div>
                                <x-input-label for="permissions" :value="__('Permisos:')" />
                                <p class="text-sm text-gray-600 mb-2">{{ __('Solo puedes asignar los permisos que tú posees.') }}</p>
                                <div class="mt-2 grid grid-cols-2 md:grid-cols-3 gap-2">
                                    @foreach ($permissions as $permission)
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                {{ in_array($permission->id, old('permissions', $selectedPermissions)) ? 'checked' : '' }}>
                                            <span class="ml-2">{{ \App\Helpers\PermissionHelper::getFriendlyName($permission->name) }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <x-input-error :messages="$errors->get('permissions')" class="mt-2" />
                            </div>
                        </div>
                        
                        <!-- Organización -->
                        <div>
                            <x-input-label for="organizations_id" :value="__('Organización:')" />
                            <select id="organizations_id" name="organizations_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">{{ __('Seleccionar organización') }}</option>
                                @foreach ($organizations as $organization)
                                    <option value="{{ $organization->id }}" {{ old('organizations_id', $supervisedUser->organizations_id) == $organization->id ? 'selected' : '' }}>
                                        {{ $organization->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('organizations_id')" class="mt-2" />
                        </div>
                        
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('supervised-users.show', $supervisedUser) }}" class="text-gray-600 hover:text-gray-900 mr-4">
                                {{ __('Cancelar') }}
                            </a>
                            <x-primary-button>
                                {{ __('Actualizar Usuario') }}
                            </x-primary-button>
                        </div>
                    </form>
                    
                    <!-- Cambiar Contraseña -->
                    <div class="mt-8 pt-6 border-t">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Cambiar Contraseña') }}</h3>
                        <form action="{{ route('supervised-users.update-password', $supervisedUser) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="password" :value="__('Nueva Contraseña')" />
                                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>
                                
                                <div>
                                    <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />
                                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
                                </div>
                            </div>
                            
                            <div class="flex justify-end mt-4">
                                <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">
                                    {{ __('Cambiar Contraseña') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
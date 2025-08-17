<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Usuario Supervisado') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('supervised-users.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Información de Acceso') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Email -->
                                <div>
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>
                                
                                <!-- Username -->
                                <div>
                                    <x-input-label for="user_name" :value="__('Nombre de usuario')" />
                                    <x-text-input id="user_name" class="block mt-1 w-full" type="text" name="user_name" :value="old('user_name')" required />
                                    <x-input-error :messages="$errors->get('user_name')" class="mt-2" />
                                </div>
                                
                                <!-- Password -->
                                <div>
                                    <x-input-label for="password" :value="__('Contraseña')" />
                                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Información Personal') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Name -->
                                <div>
                                    <x-input-label for="name" :value="__('Nombre:')" />
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>
                                
                                <!-- Last Name -->
                                <div>
                                    <x-input-label for="last_name" :value="__('Apellido:')" />
                                    <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required />
                                    <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                                </div>
                                
                                <!-- Phone -->
                                <div>
                                    <x-input-label for="phone" :value="__('Teléfono:')" />
                                    <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" required />
                                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                                </div>
                                
                                <!-- Address -->
                                <div>
                                    <x-input-label for="address" :value="__('Dirección:')" />
                                    <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" required />
                                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                </div>
                                
                                <!-- Sex -->
                                <div>
                                    <x-input-label for="sex" :value="__('Género:')" />
                                    <select id="sex" name="sex" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                        <option value="">{{ __('Seleccionar') }}</option>
                                        <option value="M" {{ old('sex') == 'M' ? 'selected' : '' }}>{{ __('Masculino') }}</option>
                                        <option value="F" {{ old('sex') == 'F' ? 'selected' : '' }}>{{ __('Femenino') }}</option>
                                        <option value="O" {{ old('sex') == 'O' ? 'selected' : '' }}>{{ __('Otro') }}</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('sex')" class="mt-2" />
                                </div>
                                
                                <!-- Age -->
                                <div>
                                    <x-input-label for="age" :value="__('Edad:')" />
                                    <x-text-input id="age" class="block mt-1 w-full" type="number" name="age" :value="old('age')" required min="18" />
                                    <x-input-error :messages="$errors->get('age')" class="mt-2" />
                                </div>
                                
                                <!-- Type DNI -->
                                <div>
                                    <x-input-label for="type_dni" :value="__('Tipo de Identificación:')" />
                                    <select id="type_dni" name="type_dni" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                        <option value="">{{ __('Seleccionar') }}</option>
                                        <option value="V" {{ old('type_dni') == 'V' ? 'selected' : '' }}>{{ __('V') }}</option>
                                        <option value="E" {{ old('type_dni') == 'E' ? 'selected' : '' }}>{{ __('E') }}</option>
                                        <option value="J" {{ old('type_dni') == 'J' ? 'selected' : '' }}>{{ __('J') }}</option>
                                        <option value="G" {{ old('type_dni') == 'G' ? 'selected' : '' }}>{{ __('G') }}</option>
                                        <option value="P" {{ old('type_dni') == 'P' ? 'selected' : '' }}>{{ __('P') }}</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('type_dni')" class="mt-2" />
                                </div>
                                
                                <!-- DNI -->
                                <div>
                                    <x-input-label for="dni" :value="__('Identificación:')" />
                                    <x-text-input id="dni" class="block mt-1 w-full" type="text" name="dni" :value="old('dni')" required />
                                    <x-input-error :messages="$errors->get('dni')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                        
                        <!-- Institución -->
                        <div class="mb-4">
                            <x-input-label for="organizations_id" :value="__('Organización:')" />
                            <select name="organizations_id" id="organizations_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">{{ __('Seleccione una organización') }}</option>
                                @foreach($organizations as $organization)
                                    <option value="{{ $organization->id }}" {{ old('organizations_id') == $organization->id ? 'selected' : '' }}>
                                        {{ $organization->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('organizations_id')" class="mt-2" />
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold mb-4">{{ __('Permisos') }}</h3>
                            
                            <!-- Campo oculto para asignar el rol Admin por defecto -->
                            @foreach ($roles as $role)
                                @if ($role->name === 'Admin')
                                    <input type="hidden" name="roles[]" value="{{ $role->id }}">
                                @endif
                            @endforeach
                            
                            <!-- Permissions -->
                            <div>
                                <x-input-label for="permissions" :value="__('Permisos')" />
                                <p class="text-sm text-gray-600 mb-2">{{ __('Solo puedes asignar los permisos que tú posees.') }}</p>
                                <div class="mt-2 grid grid-cols-2 md:grid-cols-3 gap-2">
                                    @foreach ($permissions as $permission)
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                            <span class="ml-2">{{ \App\Helpers\PermissionHelper::getFriendlyName($permission->name) }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <x-input-error :messages="$errors->get('permissions')" class="mt-2" />
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('supervised-users.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                                {{ __('Cancelar') }}
                            </a>
                            <x-primary-button>
                                {{ __('Crear Usuario') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
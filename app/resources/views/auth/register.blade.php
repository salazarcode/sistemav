<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold text-gray-900 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            {{ config('app.name', 'Laravel') }}
        </h1>
        <p class="text-gray-600 mt-2">{{ __('Registro de Usuario Master') }}</p>
    </div>
    
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="grid grid-cols-1 gap-4">
            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Nombre:')" />
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <x-text-input id="name" class="block mt-1 w-full pl-10" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="{{ __('Ingresa tu nombre') }}" />
                </div>
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Last Name -->
            <div>
                <x-input-label for="last_name" :value="__('Apellido:')" />
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <x-text-input id="last_name" class="block mt-1 w-full pl-10" type="text" name="last_name" :value="old('last_name')" required autocomplete="family-name" placeholder="{{ __('Ingresa tu apellido') }}" />
                </div>
                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Correo Electrónico:')" />
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <x-text-input id="email" class="block mt-1 w-full pl-10" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="{{ __('ejemplo@correo.com') }}" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Username -->
            <div>
                <x-input-label for="user_name" :value="__('Nombre de Usuario:')" />
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <x-text-input id="user_name" class="block mt-1 w-full pl-10" type="text" name="user_name" :value="old('user_name')" required autocomplete="username" placeholder="{{ __('Elige un nombre de usuario') }}" />
                </div>
                <x-input-error :messages="$errors->get('user_name')" class="mt-2" />
            </div>

            <!-- Phone -->
            <div>
                <x-input-label for="phone" :value="__('Teléfono:')" />
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <x-text-input id="phone" class="block mt-1 w-full pl-10" type="text" name="phone" :value="old('phone')" required autocomplete="tel" placeholder="{{ __('Ingresa tu número de teléfono') }}" />
                </div>
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>

            <!-- Address -->
            <div>
                <x-input-label for="address" :value="__('Dirección:')" />
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <x-text-input id="address" class="block mt-1 w-full pl-10" type="text" name="address" :value="old('address')" required autocomplete="street-address" placeholder="{{ __('Ingresa tu dirección') }}" />
                </div>
                <x-input-error :messages="$errors->get('address')" class="mt-2" />
            </div>

            <!-- Sex -->
            <div>
                <x-input-label for="sex" :value="__('Género:')" />
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <select id="sex" name="sex" class="block mt-1 w-full pl-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                        <option value="">{{ __('Seleccionar') }}</option>
                        <option value="M" {{ old('sex') == 'M' ? 'selected' : '' }}>{{ __('Masculino') }}</option>
                        <option value="F" {{ old('sex') == 'F' ? 'selected' : '' }}>{{ __('Femenino') }}</option>
                        <option value="O" {{ old('sex') == 'O' ? 'selected' : '' }}>{{ __('Otro') }}</option>
                    </select>
                </div>
                <x-input-error :messages="$errors->get('sex')" class="mt-2" />
            </div>

            <!-- Age -->
            <div>
                <x-input-label for="age" :value="__('Edad:')" />
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <x-text-input id="age" class="block mt-1 w-full pl-10" type="number" name="age" :value="old('age')" required min="18" placeholder="{{ __('Ingresa tu edad') }}" />
                </div>
                <x-input-error :messages="$errors->get('age')" class="mt-2" />
            </div>

            <!-- Type DNI -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="16" rx="2" ry="2"></rect>
                        <line x1="8" y1="2" x2="8" y2="4"></line>
                        <line x1="16" y1="2" x2="16" y2="4"></line>
                        <path d="M7 10h10"></path>
                        <path d="M7 14h10"></path>
                    </svg>
                </div>
                
                <x-input-label for="type_dni" :value="__('Tipo de Identificación:')" />
                
                <select id="type_dni" name="type_dni" class="block mt-1 w-full pl-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                    <option value="">{{ __('Seleccionar') }}</option>
                    <option value="ID" {{ old('type_dni') == 'ID' ? 'selected' : '' }}>{{ __('Documento de Identificación') }}</option>
                    <option value="Passport" {{ old('type_dni') == 'Passport' ? 'selected' : '' }}>{{ __('Pasaporte') }}</option>
                    <option value="Other" {{ old('type_dni') == 'Other' ? 'selected' : '' }}>{{ __('Otro') }}</option>
                </select>
                
                <x-input-error :messages="$errors->get('type_dni')" class="mt-2" />
            </div>

            <!-- DNI -->
            <div>
                <x-input-label for="dni" :value="__('Identificación:')" />
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                        </svg>
                    </div>
                    <x-text-input id="dni" class="block mt-1 w-full pl-10" type="text" name="dni" :value="old('dni')" required placeholder="{{ __('Ingresa tu número de identificación') }}" />
                </div>
                <x-input-error :messages="$errors->get('dni')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Contraseña')" />
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <x-text-input id="password" class="block mt-1 w-full pl-10" type="password" name="password" required autocomplete="new-password" placeholder="{{ __('Crea una contraseña segura') }}" />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <x-text-input id="password_confirmation" class="block mt-1 w-full pl-10" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="{{ __('Repite tu contraseña') }}" />
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="text-sm text-indigo-600 hover:text-indigo-800 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('¿Ya tienes una cuenta?') }}
            </a>

            <x-primary-button class="px-6 py-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                {{ __('Registrarse') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

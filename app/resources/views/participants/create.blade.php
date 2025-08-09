<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Agregar Participante') }}: {{ $event->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('events.participants.store', $event) }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <x-input-label for="name" :value="__('Nombre:')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
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
                            
                            <!-- Email -->
                            <div>
                                <x-input-label for="email" :value="__('Correo Electrónico:')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                            
                            <!-- Address -->
                            <div>
                                <x-input-label for="address" :value="__('Dirección:')" />
                                <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" required />
                                <x-input-error :messages="$errors->get('address')" class="mt-2" />
                            </div>
                            
                            <!-- Gender -->
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
                            
                            <!-- Birth Date -->
                            <div>
                                <x-input-label for="birth_date" :value="__('Fecha de Nacimiento:')" />
                                <x-text-input id="birth_date" class="block mt-1 w-full" type="date" name="birth_date" :value="old('birth_date')" required max="{{ date('Y-m-d') }}" />
                                <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
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
                        
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('events.participants.index', $event) }}" class="text-gray-600 hover:text-gray-900 mr-4">
                                {{ __('Cancelar') }}
                            </a>
                            <x-primary-button>
                                {{ __('Registrar Participante') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
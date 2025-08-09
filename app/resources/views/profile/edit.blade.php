<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Perfil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Información de Acceso -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Información Personal -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="flex justify-between items-start mb-4">
                    <h2 class="text-lg font-medium text-gray-900">
                        {{ __('Información Personal') }}
                    </h2>
                    <button type="button" id="openPersonalDataModal" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        {{ __('Editar Datos Personales') }}
                    </button>
                </div>

                @if ($personalData)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <p class="text-gray-600">
                                <span class="font-semibold">{{ __('Nombre completo') }}:</span><br>
                                {{ $personalData->name }} {{ $personalData->last_name }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-600">
                                <strong>{{ __('Identificación:') }}</strong>
                                {{ $personalData->type_dni }}: {{ $personalData->dni }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-600">
                                <span class="font-semibold">{{ __('Teléfono') }}:</span><br>
                                {{ $personalData->phone }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-600">
                                <span class="font-semibold">{{ __('Dirección') }}:</span><br>
                                {{ $personalData->address }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-600">
                                <span class="font-semibold">{{ __('Género') }}:</span><br>
                                @if ($personalData->sex == 'M')
                                    {{ __('Masculino') }}
                                @elseif ($personalData->sex == 'F')
                                    {{ __('Femenino') }}
                                @else
                                    {{ __('Otro') }}
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-600">
                                <span class="font-semibold">{{ __('Edad') }}:</span><br>
                                {{ $personalData->age }}
                            </p>
                        </div>
                    </div>
                @else
                    <div class="text-gray-500 italic">
                        {{ __('No has completado tu información personal. Haz clic en "Editar Datos Personales" para añadirla.') }}
                    </div>
                @endif
            </div>

            <!-- Actualizar Contraseña -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Eliminar Cuenta -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar datos personales -->
    <div id="personalDataModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
            <div class="flex flex-col">
                <div class="flex justify-between items-center border-b pb-3">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Editar Datos Personales') }}</h3>
                    <button id="closePersonalDataModal" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="mt-4">
                    <form method="post" action="{{ route('profile.update-personal-data') }}" class="space-y-6">
                        @csrf
                        @method('patch')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Nombre -->
                            <div>
                                <x-input-label for="name" :value="__('Nombre:')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $personalData->name ?? '')" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <!-- Apellido -->
                            <div>
                                <x-input-label for="last_name" :value="__('Apellido:')" />
                                <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" :value="old('last_name', $personalData->last_name ?? '')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Teléfono -->
                            <div>
                                <x-input-label for="phone" :value="__('Teléfono:')" />
                                <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $personalData->phone ?? '')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                            </div>

                            <!-- Dirección -->
                            <div>
                                <x-input-label for="address" :value="__('Dirección:')" />
                                <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $personalData->address ?? '')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('address')" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Tipo de DNI -->
                            <div>
                                <x-input-label for="type_dni" :value="__('Tipo de Identificación:')" />
                                <select id="type_dni" name="type_dni" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="V" {{ (old('type_dni', $personalData->type_dni ?? '') == 'V') ? 'selected' : '' }}>V</option>
                                    <option value="E" {{ (old('type_dni', $personalData->type_dni ?? '') == 'E') ? 'selected' : '' }}>E</option>
                                    <option value="J" {{ (old('type_dni', $personalData->type_dni ?? '') == 'J') ? 'selected' : '' }}>J</option>
                                    <option value="G" {{ (old('type_dni', $personalData->type_dni ?? '') == 'G') ? 'selected' : '' }}>G</option>
                                    <option value="P" {{ (old('type_dni', $personalData->type_dni ?? '') == 'P') ? 'selected' : '' }}>P</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('type_dni')" />
                            </div>

                            <!-- DNI -->
                            <div>
                                <x-input-label for="dni" :value="__('Identificación:')" />
                                <x-text-input id="dni" name="dni" type="text" class="mt-1 block w-full" :value="old('dni', $personalData->dni ?? '')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('dni')" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Género -->
                            <div>
                                <x-input-label for="gender" :value="__('Género:')" />
                                <select id="gender" name="gender" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="M" {{ (old('sex', $personalData->sex ?? '') == 'M') ? 'selected' : '' }}>Masculino</option>
                                    <option value="F" {{ (old('sex', $personalData->sex ?? '') == 'F') ? 'selected' : '' }}>Femenino</option>
                                    <option value="O" {{ (old('sex', $personalData->sex ?? '') == 'O') ? 'selected' : '' }}>Otro</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('sex')" />
                            </div>

                            <!-- Edad -->
                            <div>
                                <x-input-label for="age" :value="__('Edad:')" />
                                <x-text-input id="age" name="age" type="number" class="mt-1 block w-full" :value="old('age', $personalData->age ?? '')" min="18" max="120" required />
                                <x-input-error class="mt-2" :messages="$errors->get('age')" />
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="button" id="cancelPersonalData" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 mr-2">
                                {{ __('Cancelar') }}
                            </button>
                            <x-primary-button>{{ __('Guardar') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript para el modal -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('personalDataModal');
            const openModalBtn = document.getElementById('openPersonalDataModal');
            const closeModalBtn = document.getElementById('closePersonalDataModal');
            const cancelBtn = document.getElementById('cancelPersonalData');

            openModalBtn.addEventListener('click', function() {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            });

            const closeModal = function() {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            };

            closeModalBtn.addEventListener('click', closeModal);
            cancelBtn.addEventListener('click', closeModal);

            // Cerrar modal al hacer clic fuera del contenido
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModal();
                }
            });

            // Mostrar mensaje de éxito
            @if (session('status') === 'personal-data-updated')
                const successMessage = document.createElement('div');
                successMessage.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50 flex items-center';
                successMessage.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ __('Datos personales actualizados correctamente.') }}
                `;
                document.body.appendChild(successMessage);
                
                setTimeout(() => {
                    successMessage.remove();
                }, 3000);
            @endif
        });
    </script>
</x-app-layout>

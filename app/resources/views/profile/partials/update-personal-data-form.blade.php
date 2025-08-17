<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Información Personal') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Actualiza tu información personal.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update-personal-data') }}" class="mt-6 space-y-6">
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
                <x-input-label for="sex" :value="__('Género:')" />
                <select id="sex" name="sex" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
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

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Guardar') }}</x-primary-button>

            @if (session('status') === 'personal-data-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Guardado.') }}</p>
            @endif
        </div>
    </form>
</section> 
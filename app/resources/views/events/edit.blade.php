<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Evento') }}: {{ $event->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('events.update', $event) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nombre del evento -->
                            <div>
                                <x-input-label for="name" :value="__('Nombre del evento')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $event->name)" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            
                            <!-- Ubicación -->
                            <div>
                                <x-input-label for="location" :value="__('Ubicación')" />
                                <x-text-input id="location" class="block mt-1 w-full" type="text" name="location" :value="old('location', $event->location)" required />
                                <x-input-error :messages="$errors->get('location')" class="mt-2" />
                            </div>
                            
                            <!-- Fecha de inicio -->
                            <div>
                                <x-input-label for="start_date" :value="__('Fecha de inicio')" />
                                <x-text-input id="start_date" class="block mt-1 w-full" type="datetime-local" name="start_date" :value="old('start_date', $event->start_date->format('Y-m-d\TH:i'))" required />
                                <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                            </div>
                            
                            <!-- Fecha de fin -->
                            <div>
                                <x-input-label for="end_date" :value="__('Fecha de fin')" />
                                <x-text-input id="end_date" class="block mt-1 w-full" type="datetime-local" name="end_date" :value="old('end_date', $event->end_date->format('Y-m-d\TH:i'))" required />
                                <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                            </div>
                            
                            <!-- Categorías -->
                            <div class="md:col-span-2">
                                <x-input-label for="categories" :value="__('Categorías')" />
                                <div class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-2">
                                    @foreach ($categories as $category)
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="categories[]" value="{{ $category->id }}" 
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                {{ in_array($category->id, old('categories', $selectedCategories)) ? 'checked' : '' }}>
                                            <span class="ml-2">{{ $category->description }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <x-input-error :messages="$errors->get('categories')" class="mt-2" />
                            </div>
                            
                            <!-- Imagen actual -->
                            @if ($event->img)
                                <div class="md:col-span-2">
                                    <x-input-label :value="__('Imagen actual')" />
                                    <div class="mt-2">
                                        <img src="{{ Storage::url($event->img) }}" alt="{{ $event->name }}" class="h-40 object-cover rounded-md">
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Nueva imagen -->
                            <div class="md:col-span-2">
                                <x-input-label for="img" :value="__('Nueva imagen (opcional)')" />
                                <input id="img" type="file" name="img" class="mt-1 block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-md file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-indigo-50 file:text-indigo-700
                                    hover:file:bg-indigo-100" accept="image/*" />
                                <x-input-error :messages="$errors->get('img')" class="mt-2" />
                            </div>
                            
                            <!-- Descripción -->
                            <div class="md:col-span-2">
                                <x-input-label for="description" :value="__('Descripción')" />
                                <textarea id="description" name="description" rows="5" 
                                    class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                    required>{{ old('description', $event->description) }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('events.show', $event) }}" class="text-gray-600 hover:text-gray-900 mr-4">
                                {{ __('Cancelar') }}
                            </a>
                            <x-primary-button>
                                {{ __('Actualizar Evento') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                @if(isset($viewMode) && $viewMode == 'all')
                    {{ __('Todos los Eventos') }}
                @else
                    {{ __('Mis Eventos') }}
                @endif
            </h2>

            @if(auth()->user()->permissions->contains('name', 'create_event'))
            <a href="{{ route('events.create') }}" class="flex w-max px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('Crear Evento') }}
            </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 ">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 flex items-center" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 flex items-center" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <!-- Search Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Filtrar Eventos') }}</h3>
                    
                    <!-- Saved Filters Section -->
                    @if(count($savedFilters) > 0)
                    <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                        <h4 class="font-medium mb-2">{{ __('Filtros Guardados') }}</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($savedFilters as $name => $filter)
                                <div class="flex items-center">
                                    <a href="{{ route('events.index', ['apply_filter' => $name]) }}" 
                                       class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-l-lg text-sm hover:bg-indigo-200 border border-indigo-200">
                                        <span class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                            </svg>
                                            {{ $name }}
                                        </span>
                                    </a>
                                    <a href="{{ route('events.index', ['delete_filter' => $name]) }}" 
                                       class="px-2 py-1 bg-red-100 text-red-800 rounded-r-lg text-sm hover:bg-red-200 border border-red-200"
                                       onclick="return confirm('{{ __('¿Estás seguro de que deseas eliminar el filtro') }} \"{{ $name }}\"? {{ __('Esta acción no se puede deshacer.') }}')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    <form action="{{ route('events.index') }}" method="GET">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Nombre del evento') }}</label>
                                <input type="text" name="search" id="search" value="{{ $search ?? '' }}" placeholder="{{ __('Buscar eventos...') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Categoría') }}</label>
                                <select name="category" id="category" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">{{ __('Todas las categorías') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ ($categoryId ?? '') == $category->id ? 'selected' : '' }}>
                                            {{ $category->description }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Organization -->
                            <div>
                                <label for="organization" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Organización') }}</label>
                                <select name="organization" id="organization" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">{{ __('Todas las organizaciones') }}</option>
                                    @foreach($organizations as $organization)
                                        <option value="{{ $organization->id }}" {{ ($organizationId ?? '') == $organization->id ? 'selected' : '' }}>
                                            {{ $organization->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Date Range -->
                            <div>
                                <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Fecha desde') }}</label>
                                <input type="date" name="date_from" id="date_from" value="{{ $dateFrom ?? '' }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Fecha hasta') }}</label>
                                <input type="date" name="date_to" id="date_to" value="{{ $dateTo ?? '' }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            
                            <!-- Location -->
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Ubicación') }}</label>
                                <input type="text" name="location" id="location" value="{{ $location ?? '' }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            
                            <!-- Participants Range -->
                            <div>
                                <label for="min_participants" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Mín. participantes') }}</label>
                                <input type="number" name="min_participants" id="min_participants" value="{{ $minParticipants ?? '' }}" min="0" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label for="max_participants" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Máx. participantes') }}</label>
                                <input type="number" name="max_participants" id="max_participants" value="{{ $maxParticipants ?? '' }}" min="0" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            
                            <!-- Selector de modo de visualización -->
                            <div>
                                <label for="view_mode" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Mostrar eventos') }}</label>
                                <select name="view_mode" id="view_mode" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="own" {{ ($viewMode ?? 'own') == 'own' ? 'selected' : '' }}>{{ __('Solo mis eventos') }}</option>
                                    <option value="all" {{ ($viewMode ?? 'own') == 'all' ? 'selected' : '' }}>{{ __('Todos los eventos (incluye supervisados)') }}</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Save Filter Option -->
                        <div class="mt-4 border-t pt-4">
                            <div class="flex items-center">
                                <input id="save_filter" name="save_filter" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <label for="save_filter" class="ml-2 block text-sm text-gray-900 font-medium">{{ __('Guardar este filtro para uso futuro') }}</label>
                            </div>
                            <div class="mt-2" id="filter_name_container" style="display: none;">
                                <div class="flex items-center">
                                    <div class="">
                                        <label for="filter_name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Nombre del filtro') }}</label>
                                        <input type="text" name="filter_name" id="filter_name" placeholder="{{ __('Ej: Eventos de este mes') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div class="ml-2 mt-6">
                                        <div class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-md text-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ __('El filtro se guardará al aplicarlo') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 flex justify-end">
                            <button type="submit" class="flex w-max px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 whitespace-nowrap flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                {{ __('Aplicar Filtros') }}
                            </button>
                            
                            @if(request()->anyFilled(['search', 'category', 'date_from', 'date_to', 'location', 'min_participants', 'max_participants', 'view_mode']))
                                <a href="{{ route('events.index') }}" class="ml-3 px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    {{ __('Limpiar Filtros') }}
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Opciones de visualización -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold">{{ __('Opciones de visualización') }}</h3>
                        
                        <div class="flex flex-wrap gap-6">
                            <!-- Tipo de vista -->
                            <div>
                                <label for="view_type" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Tipo de vista') }}</label>
                                <div class="flex rounded-md shadow-sm" role="group">
                                    <button type="button" id="view-list-btn" class="view-btn px-4 py-2 text-sm font-medium rounded-l-lg border border-gray-200 hover:bg-gray-100 focus:z-10 focus:ring-2 focus:ring-indigo-500 focus:text-indigo-600 {{ (Auth::user()->preferences['events_view_type'] ?? 'list') === 'list' ? 'bg-indigo-100 text-indigo-700' : 'bg-white text-gray-700' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                        </svg>
                                    </button>
                                    <button type="button" id="view-card-btn" class="view-btn px-4 py-2 text-sm font-medium rounded-r-lg border border-gray-200 hover:bg-gray-100 focus:z-10 focus:ring-2 focus:ring-indigo-500 focus:text-indigo-600 {{ (Auth::user()->preferences['events_view_type'] ?? 'list') === 'card' ? 'bg-indigo-100 text-indigo-700' : 'bg-white text-gray-700' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Elementos por página -->
                            <div>
                                <label for="per_page" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Elementos por página') }}</label>
                                <select id="per-page-select" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="10" {{ (Auth::user()->preferences['events_per_page'] ?? 10) == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ (Auth::user()->preferences['events_per_page'] ?? 10) == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ (Auth::user()->preferences['events_per_page'] ?? 10) == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ (Auth::user()->preferences['events_per_page'] ?? 10) == 100 ? 'selected' : '' }}>100</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vista de lista -->
            <div id="list-view" class="{{ (Auth::user()->preferences['events_view_type'] ?? 'list') === 'list' ? 'block' : 'hidden' }}">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 overflow-x-auto">
                        @if ($events->count() > 0)
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Nombre') }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Fecha') }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Ubicación') }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Participantes') }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Acciones') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($events as $event)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $event->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">{{ $event->start_date->format('d/m/Y H:i') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">{{ $event->location }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">{{ $event->participants->count() }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('events.show', $event) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    {{ __('Ver') }}
                                                </a>
                                                @if(auth()->user()->permissions->contains('name', 'edit_event'))
                                                <a href="{{ route('events.edit', $event) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    {{ __('Editar') }}
                                                </a>
                                                @endif
                                                @if(auth()->user()->permissions->contains('name', 'delete_event'))
                                                <form action="{{ route('events.destroy', $event) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('{{ __('¿Estás seguro de que deseas eliminar este evento?') }}')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        {{ __('Eliminar') }}
                                                    </button>
                                                </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-gray-500">{{ __('No hay eventos disponibles.') }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Vista de tarjetas -->
            <div id="card-view" class="{{ (Auth::user()->preferences['events_view_type'] ?? 'list') === 'card' ? 'block' : 'hidden' }}">
                @if ($events->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($events as $event)
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="relative">
                                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4">
                                        <h3 class="text-white font-bold text-xl">{{ $event->name }}</h3>
                                    </div>
                                </div>
                                <div class="p-4">
                                    <div class="mb-2">
                                        <span class="text-sm text-gray-500">{{ __('Fecha') }}:</span>
                                        <span class="text-sm font-medium">{{ $event->start_date->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <span class="text-sm text-gray-500">{{ __('Ubicación') }}:</span>
                                        <span class="text-sm font-medium">{{ $event->location }}</span>
                                    </div>
                                    <div class="mb-4">
                                        <span class="text-sm text-gray-500">{{ __('Participantes') }}:</span>
                                        <span class="text-sm font-medium">{{ $event->participants->count() }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <a href="{{ route('events.show', $event) }}" class="text-indigo-600 hover:text-indigo-900">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            {{ __('Ver') }}
                                        </a>
                                        @if(auth()->user()->permissions->contains('name', 'edit_event'))
                                        <a href="{{ route('events.edit', $event) }}" class="text-yellow-600 hover:text-yellow-900">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            {{ __('Editar') }}
                                        </a>
                                        @endif
                                        @if(auth()->user()->permissions->contains('name', 'delete_event'))
                                        <form action="{{ route('events.destroy', $event) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('{{ __('¿Estás seguro de que deseas eliminar este evento?') }}')">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                {{ __('Eliminar') }}
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <p class="text-gray-500">{{ __('No hay eventos disponibles.') }}</p>
                    </div>
                @endif
            </div>

            <!-- Paginación -->
            <div class="mt-6">
                {{ $events->links() }}
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const saveFilterCheckbox = document.getElementById('save_filter');
            const filterNameContainer = document.getElementById('filter_name_container');
            const listViewBtn = document.getElementById('view-list-btn');
            const cardViewBtn = document.getElementById('view-card-btn');
            const listView = document.getElementById('list-view');
            const cardView = document.getElementById('card-view');
            const perPageSelect = document.getElementById('per-page-select');
            
            if (saveFilterCheckbox && filterNameContainer) {
                saveFilterCheckbox.addEventListener('change', function() {
                    filterNameContainer.style.display = this.checked ? 'block' : 'none';
                });
            }
            
            // Cambiar tipo de vista
            function updateViewType(viewType) {
                fetch('{{ route("user-preferences.save") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        key: 'events_view_type',
                        value: viewType
                    })
                }).then(response => {
                    if (response.ok) {
                        if (viewType === 'list') {
                            listView.classList.remove('hidden');
                            cardView.classList.add('hidden');
                            listViewBtn.classList.add('bg-indigo-100', 'text-indigo-700');
                            cardViewBtn.classList.remove('bg-indigo-100', 'text-indigo-700');
                        } else {
                            listView.classList.add('hidden');
                            cardView.classList.remove('hidden');
                            listViewBtn.classList.remove('bg-indigo-100', 'text-indigo-700');
                            cardViewBtn.classList.add('bg-indigo-100', 'text-indigo-700');
                        }
                    }
                });
            }
            
            // Cambiar elementos por página
            function updatePerPage(perPage) {
                fetch('{{ route("user-preferences.save") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        key: 'events_per_page',
                        value: perPage
                    })
                }).then(response => {
                    if (response.ok) {
                        window.location.reload();
                    }
                });
            }
            
            // Event listeners
            if (listViewBtn) {
                listViewBtn.addEventListener('click', function() {
                    updateViewType('list');
                });
            }
            
            if (cardViewBtn) {
                cardViewBtn.addEventListener('click', function() {
                    updateViewType('card');
                });
            }
            
            if (perPageSelect) {
                perPageSelect.addEventListener('change', function() {
                    updatePerPage(this.value);
                });
            }
        });
    </script>
</x-app-layout> 
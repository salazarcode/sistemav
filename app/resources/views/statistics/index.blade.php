<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Estadísticas') }}
        </h2>
    </x-slot>

    <style>
        .filter-section-toggle {
            transition: background-color 0.2s;
        }
        .filter-section-toggle:hover {
            background-color: #f3f4f6;
        }
        .filter-section-toggle svg {
            transition: transform 0.3s ease;
        }
        .filter-section-toggle svg.rotate-180 {
            transform: rotate(180deg);
        }
        .filter-section {
            transition: all 0.3s ease;
        }
        .space-x-3 > * {
            margin-left: 0.75rem;
        }
        .space-x-3 > *:first-child {
            margin-left: 0;
        }
        /* Estilos para el selector múltiple */
        select[multiple] option {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        select[multiple] option:hover {
            background-color: #f3f4f6;
        }
        select[multiple] option:checked {
            background-color: #e0e7ff;
            color: #4f46e5;
        }
    </style>

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
            
            <!-- Filtros de Estadísticas -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h2 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block mr-2 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        {{ __('Filtrar Estadísticas') }}
                    </h2>
                    
                    <form action="{{ route('statistics.index') }}" method="GET" id="statistics-filter-form">
                        <input type="hidden" name="active_section" id="active_section" value="{{ request('active_section', 'general-filters') }}">
                        
                        <!-- Acordeón de filtros -->
                        <div class="space-y-4">
                            <!-- 1. Filtros Generales -->
                            <div class="border rounded-md overflow-hidden">
                                <button type="button" class="w-full bg-gray-100 px-4 py-2 text-left font-medium flex justify-between items-center filter-section-toggle" 
                                        data-section="general-filters">
                                    <span>{{ __('Filtros Generales') }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform" id="general-filters-icon" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div id="general-filters" class="filter-section px-4 py-3">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Categoría') }}</label>
                                            <select name="category" id="category" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="">{{ __('Todas las categorías') }}</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->description }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div>
                                            <label for="period" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Período') }}</label>
                                            <select name="period" id="period" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="quarter" {{ $period == 'quarter' ? 'selected' : '' }}>{{ __('Último trimestre') }}</option>
                                                <option value="year" {{ $period == 'year' ? 'selected' : '' }}>{{ __('Último año') }}</option>
                                                <option value="month" {{ $period == 'month' ? 'selected' : '' }}>{{ __('Último mes') }}</option>
                                                <option value="all" {{ $period == 'all' ? 'selected' : '' }}>{{ __('Todo el tiempo') }}</option>
                                                <option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>{{ __('Personalizado') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div id="date-range-container" class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4" style="{{ $period == 'custom' ? '' : 'display: none;' }}">
                                        <div>
                                            <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Fecha desde') }}</label>
                                            <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                        
                                        <div>
                                            <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Fecha hasta') }}</label>
                                            <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- 2. Filtros por Organización -->
                            <div class="border rounded-md overflow-hidden">
                                <button type="button" class="w-full bg-gray-100 px-4 py-2 text-left font-medium flex justify-between items-center filter-section-toggle" 
                                        data-section="organization-filters">
                                    <span>{{ __('Filtros por Organización') }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform" id="organization-filters-icon" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div id="organization-filters" class="filter-section px-4 py-3">
                                    <div class="grid grid-cols-1 gap-4">
                                        <div>
                                            <label for="organization_search" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Buscar Organizaciones') }}</label>
                                            <input type="text" id="organization_search" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="{{ __('Buscar por nombre...') }}">
                                        </div>
                                        
                                        <div>
                                            <div class="flex justify-between items-center mb-1">
                                                <label for="organizations" class="block text-sm font-medium text-gray-700">{{ __('Organizaciones') }}</label>
                                                <span class="text-xs text-gray-500">{{ __('Selección múltiple') }}</span>
                                            </div>
                                            <select id="organizations" name="organizations[]" multiple class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                @foreach($organizations as $organizationItem)
                                                    <option value="{{ $organizationItem->id }}" {{ in_array($organizationItem->id, request('organizations', [])) ? 'selected' : '' }} class="organization-option py-1">
                                                        {{ $organizationItem->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <p class="text-xs text-gray-500 mt-1">{{ __('Mantén presionada la tecla Ctrl (o Cmd en Mac) para seleccionar múltiples organizaciones') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- 3. Filtros por Evento -->
                            <div class="border rounded-md overflow-hidden">
                                <button type="button" class="w-full bg-gray-100 px-4 py-2 text-left font-medium flex justify-between items-center filter-section-toggle" 
                                        data-section="event-filters">
                                    <span>{{ __('Filtros por Evento') }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform" id="event-filters-icon" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div id="event-filters" class="filter-section px-4 py-3">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="event_search" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Buscar Eventos') }}</label>
                                            <input type="text" name="event_search" id="event_search" value="{{ request('event_search') }}" placeholder="{{ __('Nombre del evento...') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                        
                                        <div>
                                            <label for="event_status" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Estado del Evento') }}</label>
                                            <select name="event_status" id="event_status" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="">{{ __('Todos los estados') }}</option>
                                                <option value="upcoming" {{ request('event_status') == 'upcoming' ? 'selected' : '' }}>{{ __('Próximos') }}</option>
                                                <option value="ongoing" {{ request('event_status') == 'ongoing' ? 'selected' : '' }}>{{ __('En curso') }}</option>
                                                <option value="past" {{ request('event_status') == 'past' ? 'selected' : '' }}>{{ __('Pasados') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                        <div>
                                            <label for="min_participants" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Mínimo de participantes') }}</label>
                                            <input type="number" name="min_participants" id="min_participants" value="{{ request('min_participants') }}" min="0" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                        
                                        <div>
                                            <label for="max_participants" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Máximo de participantes') }}</label>
                                            <input type="number" name="max_participants" id="max_participants" value="{{ request('max_participants') }}" min="0" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <label for="specific_events" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Eventos Específicos') }}</label>
                                        <select name="specific_events[]" id="specific_events" multiple class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" style="height: 120px;">
                                            @foreach($recentEvents ?? [] as $event)
                                                <option value="{{ $event->id }}" {{ in_array($event->id, request('specific_events', [])) ? 'selected' : '' }}>
                                                    {{ $event->name }} ({{ $event->start_date->format('d/m/Y') }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <p class="text-xs text-gray-500 mt-1">{{ __('Mantén presionada la tecla Ctrl (o Cmd en Mac) para seleccionar múltiples eventos') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-end space-x-3">
                            <div class="flex flex-col md:flex-row gap-2 w-full justify-end">
                                <button type="submit" class="flex w-full md:w-[fit-content] px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 whitespace-nowrap flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                    </svg>
                                    {{ __('Aplicar Filtros') }}
                                </button>
                                
                                    <a href="{{ route('statistics.index', ['force_refresh' => 'true']) }}" class="flex w-full md:w-[fit-content] px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                        {{ __('Limpiar Filtros') }}
                                    </a>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            @if(isset($fromCache) && $fromCache)
                                <span class="text-sm text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    {{ __('Datos cargados desde caché') }}
                                </span>
                                <a href="{{ route('statistics.clear-cache') }}" class="text-sm text-indigo-600 hover:text-indigo-800 ml-2">
                                    {{ __('Actualizar datos') }}
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Mensaje de No Datos -->
            <div id="no-data-message" class="hidden bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-8 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 mx-auto text-gray-300 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                </svg>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">No hay datos disponibles</h3>
                <p class="text-gray-500">No se encontraron datos para los filtros seleccionados. Prueba con otros criterios de búsqueda.</p>
            </div>
            
            <!-- Resumen de Estadísticas -->
            <div id="statistics-content" class="statistics-content">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h2 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">{{ __('Resumen de Eventos') }}</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-blue-700">{{ __('Total de Eventos') }}</h3>
                            <p class="text-3xl font-bold format-number">{{ $totalEvents }}</p>
                        </div>
                        
                        <div class="bg-green-50 p-4 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-green-700">{{ __('Eventos Próximos') }}</h3>
                            <p class="text-3xl font-bold format-number">{{ $upcomingEvents }}</p>
                        </div>
                        
                        <div class="bg-yellow-50 p-4 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-yellow-700">{{ __('Eventos en Curso') }}</h3>
                            <p class="text-3xl font-bold format-number">{{ $ongoingEvents }}</p>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-gray-700">{{ __('Eventos Pasados') }}</h3>
                            <p class="text-3xl font-bold format-number">{{ $pastEvents }}</p>
                        </div>
                    </div>
                    
                    <div class="flex flex-col md:flex-row gap-2 w-full justify-end my-4">
                        @if($hasReportPermission)
                            <form action="{{ route('statistics.download-excel') }}" method="POST" class="inline" id="excel-form">
                                @csrf
                                <input type="hidden" name="period" value="{{ $period }}">
                                <input type="hidden" name="date_from" value="{{ request('date_from') }}">
                                <input type="hidden" name="date_to" value="{{ request('date_to') }}">
                                <input type="hidden" name="chart_images" id="excel-chart-images-input">
                                <button type="button" id="download-excel-btn" class="flex w-full md:w-[fit-content]  inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    {{ __('Descargar Excel') }}
                                </button>
                            </form>

                            <form action="{{ route('statistics.download-pdf') }}" method="POST" class="inline" id="pdf-form">
                                @csrf
                                <input type="hidden" name="period" value="{{ $period }}">
                                <input type="hidden" name="date_from" value="{{ request('date_from') }}">
                                <input type="hidden" name="date_to" value="{{ request('date_to') }}">
                                <input type="hidden" name="chart_images" id="chart-images-input">
                                <button type="button" id="download-pdf-btn" class="flex w-full md:w-[fit-content] inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>    
                                    {{ __('Descargar PDF') }}
                                </button>
                            </form>
                        @endif
                    </div>
                    
                    <!-- Formulario oculto para enviar las imágenes de las gráficas -->
                    <form id="hidden-pdf-form" style="display: none;">
                        @csrf
                    </form>
                    
                    <!-- Switches para controlar la visualización de las gráficas -->
                    <div class="mt-6 bg-gray-50 p-4 rounded-md border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-700 mb-3">{{ __('Opciones de visualización') }}</h3>
                        
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                            <div class="flex items-center">
                                <label for="show_values" class="inline-flex items-center cursor-pointer">
                                    <span class="mr-3 text-sm font-medium text-gray-700">{{ __('Mostrar valores') }}</span>
                                    <div class="relative">
                                        <input type="checkbox" id="show_values" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </div>
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <label for="show_percentages" class="inline-flex items-center cursor-pointer">
                                    <span class="mr-3 text-sm font-medium text-gray-700">{{ __('Mostrar porcentajes') }}</span>
                                    <div class="relative">
                                        <input type="checkbox" id="show_percentages" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Gráficos -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Eventos por Categoría (Gráfico de Torta) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">{{ __('Eventos por Categoría') }}</h2>
                            <div class="chart-container" style="position: relative; height:350px;">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Eventos por Organización (Gráfico de Torta) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">{{ __('Eventos por Organización') }}</h2>
                            <div class="chart-container" style="position: relative; height:350px;">
                            <canvas id="organizationChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Eventos por Mes (Gráfico de Barras) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">{{ __('Eventos por Mes') }}</h2>
                        <div class="chart-container" style="position: relative; height:300px;">
                            <canvas id="monthChart"></canvas>
                        </div>
                    </div>
                </div>
                
                    <!-- Eventos por Participantes -->
                    <div class="mt-8 p-6 bg-white rounded-lg shadow-md">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Eventos por Cantidad de Participantes</h3>
                        <div class="h-64">
                        <canvas id="participantsChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- SECCIÓN DE ESTADÍSTICAS DE PARTICIPANTES -->
            <h2 class="text-2xl font-bold mb-4 text-gray-800">{{ __('Estadísticas de Participantes') }}</h2>
            
            <!-- Resumen de Participantes -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h2 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">{{ __('Resumen de Participantes') }}</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-purple-50 p-4 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-purple-700">{{ __('Total de Participantes') }}</h3>
                            <p class="text-3xl font-bold format-number">{{ $totalParticipants }}</p>
                        </div>
                        
                        <div class="bg-pink-50 p-4 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-pink-700">{{ __('Promedio por Evento') }}</h3>
                            <p class="text-3xl font-bold format-number">{{ $totalEvents > 0 ? round($totalParticipants / $totalEvents, 1) : 0 }}</p>
                        </div>
                        
                        <div class="bg-indigo-50 p-4 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-indigo-700">{{ __('Tasa de Asistencia') }}</h3>
                            <p class="text-3xl font-bold">{{ isset($attendanceRate) ? $attendanceRate : 0 }}%</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Gráficos de Participantes -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Participantes por Género -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">{{ __('Participantes por Género') }}</h2>
                            <div class="chart-container" style="position: relative; height:350px;">
                            <canvas id="genderChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Participantes por Edad -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">{{ __('Participantes por Edad') }}</h2>
                        <div class="chart-container" style="position: relative; height:300px;">
                            <canvas id="ageChart"></canvas>
                        </div>
                    </div>
                </div>
                
                    <!-- Participantes por Organización -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                            <h2 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">{{ __('Participantes por Organización') }}</h2>
                            <div class="chart-container" style="position: relative; height:350px;">
                                <canvas id="organizationParticipantsChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Participantes por Nivel Educativo -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">{{ __('Participantes por Nivel Educativo') }}</h2>
                            <div class="chart-container" style="position: relative; height:350px;">
                            <canvas id="educationChart"></canvas>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Participantes por Categoría -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">{{ __('Participantes por Categoría') }}</h2>
                    <div class="chart-container" style="position: relative; height:350px;">
                        <canvas id="categoryParticipantsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal para generación de PDF -->
    <div id="pdf-modal" class="fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-md mx-auto">
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-indigo-500 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Generando PDF</h3>
                <p class="mt-2 text-sm text-gray-500">Su PDF se está generando y comenzará a descargarse automáticamente. Por favor, espere pacientemente a que se complete la descarga.</p>
                <button id="close-pdf-modal" class="mt-4 flex w-max px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 whitespace-nowrap">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
    
    <!-- Modal para generación de Excel -->
    <div id="excel-modal" class="fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-md mx-auto">
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-green-500 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Generando Excel</h3>
                <p class="mt-2 text-sm text-gray-500">Su archivo Excel se está generando y comenzará a descargarse automáticamente. Por favor, espere pacientemente a que se complete la descarga.</p>
                <button id="close-excel-modal" class="mt-4 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <!-- ChartJS DataLabels Plugin -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    
    <script>
        // Registrar el plugin DataLabels globalmente
        Chart.register(ChartDataLabels);
        
        // Función para formatear números con separadores de miles y decimales
        function formatNumber(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ',00';
        }
        
        // Formato específico para porcentajes
        function formatPercentage(value) {
            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + '%';
        }
        
        // Función para manejar el acordeón de filtros
        function toggleSection(sectionId, updateActiveSection = true) {
            const sections = document.querySelectorAll('.filter-section');
            const icons = document.querySelectorAll('.filter-section-toggle svg');
            
            // Cerrar todas las secciones y rotar todos los iconos
            sections.forEach(section => {
                if (section.id !== sectionId) {
                    section.style.display = 'none';
                }
            });
            
            icons.forEach(icon => {
                if (icon.id !== sectionId + '-icon') {
                    icon.classList.add('rotate-180');
                }
            });
            
            // Abrir/cerrar la sección seleccionada
            const section = document.getElementById(sectionId);
            const icon = document.getElementById(sectionId + '-icon');
            
            if (section.style.display === 'none') {
                section.style.display = 'block';
                icon.classList.remove('rotate-180');
                
                // Actualizar el campo oculto con la sección activa
                if (updateActiveSection) {
                    document.getElementById('active_section').value = sectionId;
                }
            } else {
                section.style.display = 'none';
                icon.classList.add('rotate-180');
            }
        }
        
        // Manejo de paginación y filtros
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar secciones del acordeón
            const activeSection = document.getElementById('active_section').value;
            const sections = ['general-filters', 'organization-filters', 'event-filters'];
            
            sections.forEach(sectionId => {
                const section = document.getElementById(sectionId);
                const icon = document.getElementById(sectionId + '-icon');
                
                if (sectionId === activeSection) {
                    section.style.display = 'block';
                    icon.classList.remove('rotate-180');
                    } else {
                    section.style.display = 'none';
                    icon.classList.add('rotate-180');
                    }
                });
            
            // Configurar los botones de toggle para las secciones
            const toggleButtons = document.querySelectorAll('.filter-section-toggle');
            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const sectionId = this.getAttribute('data-section');
                    toggleSection(sectionId);
                });
            });
            
            // Inicializar el botón para cerrar la modal
            const closePdfModalBtn = document.getElementById('close-pdf-modal');
            if (closePdfModalBtn) {
                closePdfModalBtn.addEventListener('click', function() {
                    const pdfModal = document.getElementById('pdf-modal');
                    pdfModal.classList.add('hidden');
                });
            }
            
            // Resto del código de inicialización...
        });
        
        // Variables globales para controlar la visualización
        let showValues = false;
        let showPercentages = false;

        // Configuración común para gráficos de tipo pie con valores visibles
        function getPieChartOptions() {
            return {
                responsive: true,
                maintainAspectRatio: false,
                // Reducir el tamaño del gráfico circular para dejar más espacio para las etiquetas
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.raw || 0;
                                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                let percentage = Math.round((value / total) * 100);
                                return `${label}: ${formatNumber(value)} (${formatPercentage(percentage)})`;
                            }
                        }
                    },
                    datalabels: {
                        formatter: (value, ctx) => {
                            let sum = ctx.dataset.data.reduce((a, b) => a + b, 0);
                            let percentage = Math.round((value / sum) * 100);
                            
                            if (showValues && showPercentages) {
                                return formatNumber(value) + ' (' + formatPercentage(percentage) + ')';
                            } else if (showValues) {
                                return formatNumber(value);
                            } else if (showPercentages) {
                                return formatPercentage(percentage);
                            } else {
                                return null; // No mostrar nada
                            }
                        },
                        color: '#000', // Cambiar color del texto a negro
                        font: {
                            weight: 'bold',
                            size: 11
                        },
                        textStrokeColor: '#fff', // Agregar contorno blanco para mejor legibilidad
                        textStrokeWidth: 1,
                        display: function(context) {
                            // No mostrar si el valor es 0 o si ambos switches están desactivados
                            return context.dataset.data[context.dataIndex] > 0 && (showValues || showPercentages);
                        },
                        padding: 6,
                        // Ajustes para posicionar las etiquetas más hacia afuera
                        anchor: 'end',
                        align: 'end',
                        offset: 15 // Incrementar el offset para alejar más las etiquetas
                    }
                },
                // Reducir el radio del gráfico para dejar más espacio a las etiquetas
                radius: '70%', // Reducir el tamaño del gráfico al 70% del espacio disponible
                layout: {
                    padding: 20
                }
            };
        }
        
        // Configuración común para gráficos de tipo bar con valores visibles
        function getBarChartOptions(yAxisFormatter = null) {
            return {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            callback: yAxisFormatter || function(value) {
                                return formatNumber(value);
                            }
                        }
                    }
                },
                plugins: {
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        formatter: function(value) {
                            if (!showValues || value == 0) {
                                return null;
                            }
                            return formatNumber(value);
                        },
                        font: {
                            weight: 'bold',
                            size: 12
                        },
                        color: '#000', // Cambiar a negro
                        padding: {
                            top: 10,
                            bottom: 0
                        },
                        offset: 5,
                        display: function(context) {
                            return context.dataset.data[context.dataIndex] > 0 && showValues;
                        }
                    }
                },
                layout: {
                    padding: {
                        top: 20,
                        right: 0,
                        bottom: 0,
                        left: 0
                    }
                }
            };
        }
        
        // Manejo de los switches de visualización
        document.addEventListener('DOMContentLoaded', function() {
            const showValuesSwitch = document.getElementById('show_values');
            const showPercentagesSwitch = document.getElementById('show_percentages');
            
            // Escuchar cambios en los switches
            showValuesSwitch.addEventListener('change', function() {
                showValues = this.checked;
                updateAllCharts();
            });
            
            showPercentagesSwitch.addEventListener('change', function() {
                showPercentages = this.checked;
                updateAllCharts();
            });
            
            // Función para actualizar todos los gráficos
            function updateAllCharts() {
                // Actualizar todos los gráficos existentes
                Object.values(Chart.instances).forEach(chart => {
                    // Recalcular opciones basado en el tipo
                    if (chart.config.type === 'pie') {
                        chart.options = getPieChartOptions();
                    } else if (chart.config.type === 'bar') {
                        // Mantener el formateador personalizado si existe
                        const currentFormatter = chart.options.scales?.y?.ticks?.callback;
                        chart.options = getBarChartOptions(currentFormatter);
                    }
                    chart.update();
                });
            }
        });
        
        // Inicialización de gráficos
        document.addEventListener('DOMContentLoaded', function() {
            // Verificar si los elementos canvas existen
            const categoryChartElement = document.getElementById('categoryChart');
            const organizationChartElement = document.getElementById('organizationChart');
            const monthChartElement = document.getElementById('monthChart');
            const participantsChartElement = document.getElementById('participantsChart');
            
            // Nuevos elementos canvas para participantes
            const genderChartElement = document.getElementById('genderChart');
            const ageChartElement = document.getElementById('ageChart');
            const organizationParticipantsChartElement = document.getElementById('organizationParticipantsChart');
            const educationChartElement = document.getElementById('educationChart');
            const attendanceChartElement = document.getElementById('attendanceChart');
            
            // Nuevos elementos para análisis cruzado
            const categoryParticipantsChartElement = document.getElementById('categoryParticipantsChart');
            const monthParticipantsChartElement = document.getElementById('monthParticipantsChart');
            
            console.log('Inicializando gráficos...');
            
            // Datos para el gráfico de categorías
            const categoryData = @json($eventsByCategory);
            console.log('Datos de categorías:', categoryData);
            
            if (categoryChartElement && categoryData.length > 0) {
                const categoryLabels = categoryData.map(item => item.name);
                const categoryValues = categoryData.map(item => item.count);
                const categoryColors = generateColors(categoryData.length);
                
                // Gráfico de Categorías (Torta)
                new Chart(
                    categoryChartElement,
                    {
                        type: 'pie',
                        data: {
                            labels: categoryLabels,
                            datasets: [{
                                data: categoryValues,
                                backgroundColor: categoryColors,
                                hoverOffset: 4
                            }]
                        },
                        options: getPieChartOptions()
                    }
                );
            } else {
                console.log('No hay datos de categorías o el elemento no existe');
                if (categoryChartElement) {
                    categoryChartElement.parentNode.innerHTML = '<div class="flex items-center justify-center h-full"><p class="text-gray-500">No hay datos disponibles</p></div>';
                }
            }
            
            // Datos para el gráfico de organizaciones
            const organizationData = @json($eventsByOrganization);
            console.log('Datos de organizaciones:', organizationData);
            
            if (organizationChartElement && organizationData.length > 0) {
                const organizationLabels = organizationData.map(item => item.name);
                const organizationValues = organizationData.map(item => item.count);
                const organizationColors = generateColors(organizationData.length);
                
                // Gráfico de Organizaciones (Torta)
                new Chart(
                    organizationChartElement,
                    {
                        type: 'pie',
                        data: {
                            labels: organizationLabels,
                            datasets: [{
                                data: organizationValues,
                                backgroundColor: organizationColors,
                                hoverOffset: 4
                            }]
                        },
                        options: getPieChartOptions()
                    }
                );
            } else {
                console.log('No hay datos de organizaciones o el elemento no existe');
                if (organizationParticipantsChartElement) {
                    organizationParticipantsChartElement.parentNode.innerHTML = '<div class="flex items-center justify-center h-full"><p class="text-gray-500">No hay datos disponibles</p></div>';
                }
            }
            
            // Datos para el gráfico de meses
            const monthData = @json($eventsByMonth);
            console.log('Datos de meses:', monthData);
            
            if (monthChartElement) {
                const monthLabels = monthData.map(item => item.month);
                const monthValues = monthData.map(item => item.count);
                
                // Gráfico de Meses (Barras)
                new Chart(
                    monthChartElement,
                    {
                        type: 'bar',
                        data: {
                            labels: monthLabels,
                            datasets: [{
                                label: 'Eventos',
                                data: monthValues,
                                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                                borderColor: 'rgb(54, 162, 235)',
                                borderWidth: 1
                            }]
                        },
                        options: getBarChartOptions()
                    }
                );
            } else {
                console.log('El elemento del gráfico de meses no existe');
            }
            
            // Datos para el gráfico de participantes
            const participantsData = @json($eventsByParticipants);
            console.log('Datos de participantes:', participantsData);
            
            if (participantsChartElement && participantsData.length > 0) {
                const participantsLabels = participantsData.map(item => item.name);
                const participantsValues = participantsData.map(item => item.count);
                
                // Gráfico de Participantes (Barras)
                new Chart(
                    participantsChartElement,
                    {
                        type: 'bar',
                        data: {
                            labels: participantsLabels,
                            datasets: [{
                                label: 'Participantes',
                                data: participantsValues,
                                backgroundColor: 'rgba(153, 102, 255, 0.5)',
                                borderColor: 'rgb(153, 102, 255)',
                                borderWidth: 1
                            }]
                        },
                        options: getBarChartOptions()
                    }
                );
            } else {
                console.log('No hay datos de participantes o el elemento no existe');
                if (participantsChartElement) {
                    participantsChartElement.parentNode.innerHTML = '<div class="flex items-center justify-center h-full"><p class="text-gray-500">No hay datos disponibles</p></div>';
                }
            }
            
            // NUEVOS GRÁFICOS DE PARTICIPANTES
            
            // Datos para el gráfico de género
            const genderData = @json($participantsByGender);
            console.log('Datos de género:', genderData);
            
            if (genderChartElement && genderData.length > 0) {
                const genderLabels = genderData.map(item => item.name);
                const genderValues = genderData.map(item => item.count);
                const genderColors = ['rgba(54, 162, 235, 0.7)', 'rgba(255, 99, 132, 0.7)', 'rgba(200, 200, 200, 0.7)'];
                
                // Gráfico de Género (Torta)
                new Chart(
                    genderChartElement,
                    {
                        type: 'pie',
                        data: {
                            labels: genderLabels,
                            datasets: [{
                                data: genderValues,
                                backgroundColor: genderColors,
                                hoverOffset: 4
                            }]
                        },
                        options: getPieChartOptions()
                    }
                );
            } else {
                console.log('No hay datos de género o el elemento no existe');
                if (genderChartElement) {
                    genderChartElement.parentNode.innerHTML = '<div class="flex items-center justify-center h-full"><p class="text-gray-500">No hay datos disponibles</p></div>';
                }
            }
            
            // Datos para el gráfico de edad
            const ageData = @json($participantsByAge);
            console.log('Datos de edad:', ageData);
            
            if (ageChartElement && ageData.length > 0) {
                const ageLabels = ageData.map(item => item.name);
                const ageValues = ageData.map(item => item.count);
                
                // Gráfico de Edad (Barras)
                new Chart(
                    ageChartElement,
                    {
                        type: 'bar',
                        data: {
                            labels: ageLabels,
                            datasets: [{
                                label: 'Participantes',
                                data: ageValues,
                                backgroundColor: 'rgba(255, 159, 64, 0.5)',
                                borderColor: 'rgb(255, 159, 64)',
                                borderWidth: 1
                            }]
                        },
                        options: getBarChartOptions()
                    }
                );
            } else {
                console.log('No hay datos de edad o el elemento no existe');
                if (ageChartElement) {
                    ageChartElement.parentNode.innerHTML = '<div class="flex items-center justify-center h-full"><p class="text-gray-500">No hay datos disponibles</p></div>';
                }
            }
            
            // Datos para el gráfico de organizaciones
            const organizationParticipantsData = @json($participantsByOrganization ?? []);
            console.log('Datos de organizaciones (participantes):', organizationParticipantsData);
            
            if (organizationParticipantsChartElement && organizationParticipantsData && organizationParticipantsData.length > 0) {
                const organizationLabels = organizationParticipantsData.map(item => item.name);
                const organizationValues = organizationParticipantsData.map(item => item.count);
                const organizationColors = generateColors(organizationParticipantsData.length);
                
                // Gráfico de Organizaciones (Torta)
                new Chart(
                    organizationParticipantsChartElement,
                    {
                        type: 'pie',
                        data: {
                            labels: organizationLabels,
                            datasets: [{
                                data: organizationValues,
                                backgroundColor: organizationColors,
                                hoverOffset: 4
                            }]
                        },
                        options: getPieChartOptions()
                    }
                );
            } else {
                console.log('No hay datos de organizaciones o el elemento no existe');
                if (organizationParticipantsChartElement) {
                    organizationParticipantsChartElement.parentNode.innerHTML = '<div class="flex items-center justify-center h-full"><p class="text-gray-500">No hay datos disponibles</p></div>';
                }
            }
            
            // Datos para el gráfico de nivel educativo
            const educationData = @json($participantsByEducation);
            console.log('Datos de nivel educativo:', educationData);
            
            if (educationChartElement && educationData.length > 0) {
                const educationLabels = educationData.map(item => item.name);
                const educationValues = educationData.map(item => item.count);
                const educationColors = generateColors(educationData.length);
                
                // Gráfico de Nivel Educativo (Torta)
                new Chart(
                    educationChartElement,
                    {
                        type: 'pie',
                        data: {
                            labels: educationLabels,
                            datasets: [{
                                data: educationValues,
                                backgroundColor: educationColors,
                                hoverOffset: 4
                            }]
                        },
                        options: getPieChartOptions()
                    }
                );
            } else {
                console.log('No hay datos de nivel educativo o el elemento no existe');
                if (educationChartElement) {
                    educationChartElement.parentNode.innerHTML = '<div class="flex items-center justify-center h-full"><p class="text-gray-500">No hay datos disponibles</p></div>';
                }
            }
            
            // Datos para el gráfico de tasa de asistencia
            const attendanceData = @json($attendanceRateByCategory);
            console.log('Datos de tasa de asistencia:', attendanceData);
            
            if (attendanceChartElement && attendanceData.length > 0) {
                const attendanceLabels = attendanceData.map(item => item.name);
                const attendanceValues = attendanceData.map(item => item.rate);
                
                // Gráfico de Tasa de Asistencia (Barras)
                new Chart(
                    attendanceChartElement,
                    {
                        type: 'bar',
                        data: {
                            labels: attendanceLabels,
                            datasets: [{
                                label: 'Tasa de Asistencia (%)',
                                data: attendanceValues,
                                backgroundColor: 'rgba(255, 206, 86, 0.5)',
                                borderColor: 'rgb(255, 206, 86)',
                                borderWidth: 1
                            }]
                        },
                        options: getBarChartOptions(function(value) {
                                            return value + '%';
                        })
                    }
                );
            } else {
                console.log('No hay datos de tasa de asistencia o el elemento no existe');
                if (attendanceChartElement) {
                    attendanceChartElement.parentNode.innerHTML = '<div class="flex items-center justify-center h-full"><p class="text-gray-500">No hay datos disponibles</p></div>';
                }
            }
            
            // Nuevos gráficos para análisis cruzado
            
            // Datos para el gráfico de participantes por categoría
            const categoryParticipantsData = @json($participantsByCategory ?? []);
            console.log('Datos de participantes por categoría:', categoryParticipantsData);
            
            if (categoryParticipantsChartElement && categoryParticipantsData && categoryParticipantsData.length > 0) {
                const categoryLabels = categoryParticipantsData.map(item => item.name);
                const categoryValues = categoryParticipantsData.map(item => item.count);
                
                // Gráfico de Participantes por Categoría (Barras)
                new Chart(
                    categoryParticipantsChartElement,
                    {
                        type: 'bar',
                        data: {
                            labels: categoryLabels,
                            datasets: [{
                                label: 'Participantes',
                                data: categoryValues,
                                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                                borderColor: 'rgb(75, 192, 192)',
                                borderWidth: 1
                            }]
                        },
                        options: getBarChartOptions()
                    }
                );
            } else {
                console.log('No hay datos de participantes por categoría o el elemento no existe');
                if (categoryParticipantsChartElement) {
                    categoryParticipantsChartElement.parentNode.innerHTML = '<div class="flex items-center justify-center h-full"><p class="text-gray-500">No hay datos disponibles</p></div>';
                }
            }
            
            // Datos para el gráfico de participantes por mes
            const monthParticipantsData = @json($participantsByMonth ?? []);
            console.log('Datos de participantes por mes:', monthParticipantsData);
            
            if (monthParticipantsChartElement && monthParticipantsData && monthParticipantsData.length > 0) {
                const monthLabels = monthParticipantsData.map(item => item.month);
                const monthValues = monthParticipantsData.map(item => item.count);
                
                // Gráfico de Participantes por Mes (Barras)
                new Chart(
                    monthParticipantsChartElement,
                    {
                        type: 'bar',
                        data: {
                            labels: monthLabels,
                            datasets: [{
                                label: 'Participantes',
                                data: monthValues,
                                backgroundColor: 'rgba(153, 102, 255, 0.5)',
                                borderColor: 'rgb(153, 102, 255)',
                                borderWidth: 1
                            }]
                        },
                        options: getBarChartOptions()
                    }
                );
            } else {
                console.log('No hay datos de participantes por mes o el elemento no existe');
                if (monthParticipantsChartElement) {
                    monthParticipantsChartElement.parentNode.innerHTML = '<div class="flex items-center justify-center h-full"><p class="text-gray-500">No hay datos disponibles</p></div>';
                }
            }
            
            // Función para generar colores aleatorios
            function generateColors(count) {
                const colors = [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)',
                    'rgba(199, 199, 199, 0.7)',
                    'rgba(83, 102, 255, 0.7)',
                    'rgba(40, 159, 64, 0.7)',
                    'rgba(210, 199, 199, 0.7)',
                ];
                
                // Si necesitamos más colores de los predefinidos, los generamos
                if (count > colors.length) {
                    for (let i = colors.length; i < count; i++) {
                        const r = Math.floor(Math.random() * 255);
                        const g = Math.floor(Math.random() * 255);
                        const b = Math.floor(Math.random() * 255);
                        colors.push(`rgba(${r}, ${g}, ${b}, 0.7)`);
                    }
                }
                
                return colors.slice(0, count);
            }
        });

        // Manejar la captura de gráficos para el PDF
        document.addEventListener('DOMContentLoaded', function() {
            const downloadPdfBtn = document.getElementById('download-pdf-btn');
            const downloadExcelBtn = document.getElementById('download-excel-btn');
            
            if (downloadPdfBtn) {
                downloadPdfBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    captureCharts();
                });
            }
            
            // Manejar el evento de descarga de Excel
            if (downloadExcelBtn) {
                downloadExcelBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Mostrar la modal
                    const excelModal = document.getElementById('excel-modal');
                    excelModal.classList.remove('hidden');
                    
                    // Capturar los gráficos antes de enviar
                    captureChartsForExcel();
                    
                    // Configurar la funcionalidad para cerrar la modal
                    const closeModal = function() {
                        excelModal.classList.add('hidden');
                    };
                    
                    // El evento 'focus' se activará cuando el navegador vuelva a la ventana después de la descarga
                    window.addEventListener('focus', closeModal, { once: true });
                    
                    // También establecer un tiempo máximo para cerrar la modal (30 segundos)
                    setTimeout(closeModal, 30000);
                    
                    // Configurar el botón para cerrar la modal
                    document.getElementById('close-excel-modal').addEventListener('click', function() {
                        closeModal();
                        // Eliminar el evento focus para evitar que se cierre dos veces
                        window.removeEventListener('focus', closeModal);
                    });
                });
            }
            
            // Función para capturar todas las gráficas como imágenes Base64 para PDF
            function captureCharts() {
                const chartIds = [
                    'categoryChart',
                    'institutionChart',
                    'monthChart',
                    'participantsChart',
                    'genderChart',
                    'ageChart',
                    'institutionParticipantsChart',
                    'educationChart',
                    'attendanceChart',
                    'categoryParticipantsChart',
                    'monthParticipantsChart'
                ];
                
                const chartImagesData = {};
                let capturedCount = 0;
                
                // Mostrar la modal mientras se generan las imágenes
                const pdfModal = document.getElementById('pdf-modal');
                pdfModal.classList.remove('hidden');
                
                chartIds.forEach(function(chartId) {
                    const chartElement = document.getElementById(chartId);
                    if (chartElement) {
                        try {
                            const dataUrl = chartElement.toDataURL('image/png');
                            chartImagesData[chartId] = dataUrl;
                        } catch (error) {
                            console.error('Error al capturar gráfico ' + chartId + ': ', error);
                        }
                        
                        capturedCount++;
                        if (capturedCount >= chartIds.length) {
                            console.log('Todas las gráficas capturadas');
                            document.getElementById('chart-images-input').value = JSON.stringify(chartImagesData);
                            document.getElementById('pdf-form').submit();
                        }
                    } else {
                        capturedCount++;
                        if (capturedCount >= chartIds.length) {
                            console.log('Todas las gráficas capturadas');
                            document.getElementById('chart-images-input').value = JSON.stringify(chartImagesData);
                            document.getElementById('pdf-form').submit();
                        }
                    }
                });
            }
            
            // Función para capturar todas las gráficas como imágenes Base64 para Excel
            function captureChartsForExcel() {
                const chartIds = [
                    'categoryChart',
                    'institutionChart',
                    'monthChart',
                    'participantsChart',
                    'genderChart',
                    'ageChart',
                    'institutionParticipantsChart',
                    'educationChart',
                    'attendanceChart',
                    'categoryParticipantsChart',
                    'monthParticipantsChart'
                ];
                
                const chartImagesData = {};
                let capturedCount = 0;
                
                chartIds.forEach(function(chartId) {
                    const chartElement = document.getElementById(chartId);
                    if (chartElement) {
                        try {
                            const dataUrl = chartElement.toDataURL('image/png');
                            chartImagesData[chartId] = dataUrl;
                        } catch (error) {
                            console.error('Error al capturar gráfico ' + chartId + ': ', error);
                        }
                        
                        capturedCount++;
                        if (capturedCount >= chartIds.length) {
                            console.log('Todas las gráficas capturadas para Excel');
                            document.getElementById('excel-chart-images-input').value = JSON.stringify(chartImagesData);
                            document.getElementById('excel-form').submit();
                        }
                    } else {
                        capturedCount++;
                        if (capturedCount >= chartIds.length) {
                            console.log('Todas las gráficas capturadas para Excel');
                            document.getElementById('excel-chart-images-input').value = JSON.stringify(chartImagesData);
                            document.getElementById('excel-form').submit();
                        }
                    }
                });
            }
        });

        // Formatear todos los números con la clase format-number
        document.addEventListener('DOMContentLoaded', function() {
            // Formatear los números en la interfaz después de que el DOM esté cargado
            document.querySelectorAll('.format-number').forEach(function(element) {
                if (!isNaN(element.textContent)) {
                    element.textContent = formatNumber(parseFloat(element.textContent));
                }
            });
        });
    </script>
    @endpush
</x-app-layout> 
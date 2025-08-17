<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
            {{ __('Panel de Control') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
            
            <!-- SECCIÓN DE ESTADÍSTICAS GENERALES -->
            <section class="mb-8">
                <h2 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block mr-2 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    {{ __('Estadísticas Generales') }}
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Total de Eventos -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-blue-50">
                            <h3 class="text-lg font-semibold text-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ __('Total de Eventos') }}
                            </h3>
                            <p class="text-3xl font-bold">{{ $totalEvents }}</p>
                            <p class="text-sm text-blue-600 mt-2">{{ __('Eventos creados por ti') }}</p>
                        </div>
                    </div>
                    
                    <!-- Eventos en Curso -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-green-50">
                            <h3 class="text-lg font-semibold text-green-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ __('Eventos en Curso') }}
                            </h3>
                            <p class="text-3xl font-bold">{{ $ongoingEvents }}</p>
                            <p class="text-sm text-green-600 mt-2">{{ __('Eventos activos actualmente') }}</p>
                        </div>
                    </div>
                    
                    <!-- Total de Participantes -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-purple-50">
                            <h3 class="text-lg font-semibold text-purple-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                {{ __('Total de Participantes') }}
                            </h3>
                            <p class="text-3xl font-bold">{{ $totalParticipants }}</p>
                            <p class="text-sm text-purple-600 mt-2">{{ __('En todos tus eventos') }}</p>
                        </div>
                    </div>
                    
                    <!-- Tasa de Asistencia -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-yellow-50">
                            <h3 class="text-lg font-semibold text-yellow-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                {{ __('Tasa de Asistencia') }}
                            </h3>
                            <p class="text-3xl font-bold">{{ $attendanceRate }}%</p>
                            <p class="text-sm text-yellow-600 mt-2">{{ __('Promedio de asistencia') }}</p>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- SECCIÓN DE EVENTOS EN CURSO -->
            <section class="mb-8">
                <h2 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block mr-2 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ __('Eventos en Curso') }}
                </h2>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        @if(count($ongoingEventsList) > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Evento') }}</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Fechas') }}</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Participantes') }}</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Asistencia') }}</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Acciones') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($ongoingEventsList as $event)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $event->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $event->location }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y') }}</div>
                                                    <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($event->end_date)->format('d/m/Y') }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $event->participants_count }} {{ __('registrados') }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $event->attendance_count }} {{ __('confirmados') }}</div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $event->participants_count > 0 ? round(($event->attendance_count / $event->participants_count) * 100) : 0 }}%
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <a href="{{ route('events.show', $event) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        {{ __('Ver') }}
                                                    </a>
                                                    <a href="{{ route('events.participants.index', $event) }}" class="text-green-600 hover:text-green-900">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                        </svg>
                                                        {{ __('Participantes') }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4 text-gray-500">
                                {{ __('No hay eventos en curso actualmente.') }}
                            </div>
                            @endif
                    </div>
                </div>
            </section>
            
            <!-- SECCIÓN DE GRÁFICOS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Gráfico de Eventos por Categoría -->
                <section>
                    <h2 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block mr-2 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                        </svg>
                        {{ __('Eventos por Categoría') }}
                    </h2>
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <canvas id="eventsByCategoryChart" height="300"></canvas>
                        </div>
                    </div>
                </section>
                
                <!-- Gráfico de Participación en Eventos -->
                <section>
                    <h2 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block mr-2 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                        </svg>
                        {{ __('Participación en Eventos') }}
                    </h2>
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <canvas id="participationChart" height="300"></canvas>
                        </div>
                    </div>
                </section>
            </div>
            
            <!-- SECCIÓN DE EVENTOS DESTACADOS -->
            <section class="mt-8 mb-8">
                <h2 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block mr-2 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                    {{ __('Eventos Destacados') }}
                </h2>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Eventos con más participantes') }}</h3>
                        
                        @if($featuredEvents->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Evento') }}</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Fechas') }}</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Organización') }}</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Participantes') }}</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Categorías') }}</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Acciones') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($featuredEvents as $event)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $event->name }}</div>
                                                    <div class="text-xs text-gray-500">{{ Str::limit($event->description, 50) }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ __('Inicio') }}: {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y') }}</div>
                                                    <div class="text-sm text-gray-900">{{ __('Fin') }}: {{ \Carbon\Carbon::parse($event->end_date)->format('d/m/Y') }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $event->organization->name ?? $event->user->organization ?? __('No definida') }}</div>
                                                    @if($event->user_id !== $user->id)
                                                        <div class="text-xs text-indigo-600">{{ __('Supervisado') }}</div>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        {{ $event->participants_count }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach($event->categories as $category)
                                                            <span class="px-2 py-0.5 text-xs rounded bg-gray-100 text-gray-800">
                                                                {{ $category->description }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <a href="{{ route('events.show', $event) }}" class="text-indigo-600 hover:text-indigo-900">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        {{ __('Ver') }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4 text-gray-500">
                                {{ __('No hay eventos destacados para mostrar.') }}
                            </div>
                        @endif
                    </div>
                </div>
            </section>
            
            @if(auth()->user()->permissions->contains('name', 'create_event') || auth()->user()->permissions->contains('name', 'create_user'))
                <!-- SECCIONES 2 Y 3: EVENTOS Y SUPERVISADOS (50% ancho cada una en desktop, 100% en mobile) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    @if(auth()->user()->permissions->contains('name', 'create_event'))
                        <!-- SECCIÓN 2: EVENTOS -->
                        <section id="my-events-section">
                            <h2 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block mr-2 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ __('Mis Eventos') }}
                            </h2>
                            
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6">
                                    <!-- Resultados -->
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="text-lg font-semibold">{{ __('Eventos Recientes') }}</h3>
                                        <div class="flex items-center">
                                            <a href="{{ route('events.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                                {{ __('Crear Evento') }}
                                            </a>
                                        </div>
                                    </div>
                                    
                                    @if($userEvents->count() > 0)
                                        <!-- Vista de Lista -->
                                        <div class="list-view">
                                            <ul class="divide-y divide-gray-200">
                                                @foreach($userEvents as $event)
                                                    <li class="py-4">
                                                        <div class="flex items-start">
                                                            <div class="ml-4 flex-1">
                                                                <div class="flex items-center justify-between">
                                                                    <h4 class="text-lg font-medium text-gray-900">{{ $event->name }}</h4>
                                                                    <div class="flex space-x-2">
                                                                        <a href="{{ route('events.show', $event) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                                            </svg>
                                                                            {{ __('Ver') }}
                                                                        </a>
                                                                        <a href="{{ route('events.edit', $event) }}" class="text-gray-600 hover:text-gray-800 text-sm">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                            </svg>
                                                                            {{ __('Editar') }}
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <p class="mt-1 text-sm text-gray-600">{{ Str::limit($event->description, 100) }}</p>
                                                                <div class="mt-2 flex flex-wrap gap-1">
                                                                    @foreach($event->categories as $category)
                                                                        <span class="px-2 py-0.5 text-xs rounded bg-gray-100 text-gray-800">
                                                                            {{ $category->description }}
                                                                        </span>
                                                                    @endforeach
                                                                </div>
                                                                <div class="mt-3 flex items-center text-sm text-gray-500">
                                                                    <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                    </svg>
                                                                    {{ $event->location }}
                                                                </div>
                                                                <div class="mt-4 flex space-x-6">
                                                                    <div class="flex items-center text-sm text-gray-500">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                        </svg>
                                                                        <span>{{ __('Inicio') }}: {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y H:i') }}</span>
                                                                    </div>
                                                                    <div class="flex items-center text-sm text-gray-500">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                                        </svg>
                                                                        <span>{{ __('Fin') }}: {{ \Carbon\Carbon::parse($event->end_date)->format('d/m/Y H:i') }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        
                                        <div class="mt-4">
                                            <a href="{{ route('events.index') }}" class="text-indigo-600 hover:text-indigo-800">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                                </svg>
                                                {{ __('Ver todos mis eventos') }}
                                            </a>
                                        </div>
                                    @else
                                        <div class="text-center py-4 text-gray-500">
                                            {{ __('No has creado ningún evento aún.') }}
                                            <div class="mt-2">
                                                <a href="{{ route('events.create') }}" class="text-indigo-600 hover:text-indigo-800">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                    {{ __('Crear mi primer evento') }}
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </section>
                    @endif

                    @if(auth()->user()->permissions->contains('name', 'create_user'))
                        <!-- SECCIÓN 3: SUPERVISADOS -->
                        <section id="supervisados-section">
                            <h2 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block mr-2 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                {{ __('Supervisados') }}
                            </h2>
                            
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6">
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="text-lg font-semibold">{{ __('Supervisados Directos') }}</h3>
                                        @if($hasCreateUserPermission)
                                        <div>
                                            <a href="{{ route('supervised-users.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                                </svg>
                                                {{ __('Crear Usuario') }}
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    @if($childUsers->count() > 0)
                                        <ul class="divide-y divide-gray-200">
                                            @foreach($childUsers->take(5) as $childUser)
                                                <li class="py-4">
                                                    <div class="flex justify-between">
                                                        <div>
                                                            <h4 class="text-lg font-medium text-gray-900">{{ $childUser->user_name }}</h4>
                                                            <p class="text-sm text-gray-500">{{ $childUser->email }}</p>
                                                            <div class="mt-1">
                                                                @foreach($childUser->roles as $role)
                                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-1">
                                                                        {{ $role->name }}
                                                                    </span>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <a href="{{ route('supervised-users.show', $childUser) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                                </svg>
                                                                {{ __('Ver Detalles') }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                        
                                        <div class="mt-4">
                                            <a href="{{ route('supervised-users.index') }}" class="text-indigo-600 hover:text-indigo-800">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                                </svg>
                                                {{ __('Ver todos los supervisados') }}
                                            </a>
                                        </div>
                                    @else
                                        <div class="text-center py-4 text-gray-500">
                                            {{ __('No tienes usuarios supervisados aún.') }}
                                            @if($hasCreateUserPermission)
                                            <div class="mt-2">
                                                <a href="{{ route('supervised-users.create') }}" class="text-indigo-600 hover:text-indigo-800">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                                    </svg>
                                                    {{ __('Crear mi primer usuario supervisado') }}
                                                </a>
                                            </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </section>
                    @endif
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gráfico de Eventos por Categoría
            const eventsByCategoryCtx = document.getElementById('eventsByCategoryChart').getContext('2d');
            new Chart(eventsByCategoryCtx, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($eventsByCategory->pluck('name')) !!},
                    datasets: [{
                        data: {!! json_encode($eventsByCategory->pluck('count')) !!},
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(153, 102, 255, 0.7)',
                            'rgba(255, 159, 64, 0.7)',
                            'rgba(199, 199, 199, 0.7)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right',
                        },
                        title: {
                            display: true,
                            text: '{{ __("Distribución de eventos por categoría") }}'
                        }
                    }
                }
            });
            
            // Gráfico de Participación en Eventos
            const participationCtx = document.getElementById('participationChart').getContext('2d');
            new Chart(participationCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($participationData->pluck('name')) !!},
                    datasets: [{
                        label: '{{ __("Registrados") }}',
                        data: {!! json_encode($participationData->pluck('registered')) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }, {
                        label: '{{ __("Confirmados") }}',
                        data: {!! json_encode($participationData->pluck('confirmed')) !!},
                        backgroundColor: 'rgba(75, 192, 192, 0.7)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: '{{ __("Participantes registrados vs. confirmados") }}'
                        }
                    }
                }
            });
            
            // Manejo de guardado de filtros
            const saveFilterCheckbox = document.getElementById('save_filter');
            const filterNameContainer = document.getElementById('filter_name_container');
            
            if (saveFilterCheckbox && filterNameContainer) {
                saveFilterCheckbox.addEventListener('change', function() {
                    filterNameContainer.style.display = this.checked ? 'block' : 'none';
                });
            }
        });
    </script>
    @endpush
</x-app-layout>

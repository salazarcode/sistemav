<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap gap-4 justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight capitalize">
                {{ $event->name }}
            </h2>
            <div class="flex space-x-2 flex-wrap gap-2">
                <!-- Si el usuario es el creador del evento o el supervisor del usuario que creó el evento y ademas tiene permisos para editar eventos, puede editar el evento -->
                @if(auth()->user()->id == $event->user_id || auth()->user()->id == $event->user->supervisor_id && auth()->user()->permissions->contains('name', 'edit_event'))
                <a href="{{ route('events.edit', $event) }}" class="flex w-full md:w-max px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 whitespace-nowrap">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    {{ __('Editar Evento') }}
                </a>
                @endif
                <a href="{{ route('events.participants.create', $event) }}" class="px-4 w-full md:w-max py-2 bg-green-600 text-white rounded-md hover:bg-green-700" style="margin-left: 0px;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    {{ __('Agregar Participante') }}
                </a>
                <a href="{{ route('events.public.show', $event->slug) }}" target="_blank" class="px-4 w-full md:w-max py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700" style="margin-left: 0px;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    {{ __('Ver Página Pública') }}
                </a>
            </div>
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            @if ($event->img)
                                <div class="mb-6">
                                    <img src="{{ Storage::url($event->img) }}" alt="{{ $event->name }}" class="w-full h-64 object-cover rounded-lg">
                                </div>
                            @endif
                            
                            <h3 class="text-lg font-semibold mb-4">{{ __('Detalles del Evento') }}</h3>
                            
                            <div class="mb-4">
                                <p class="text-gray-600 mb-2">
                                    <span class="font-semibold">{{ __('Descripción') }}:</span>
                                </p>
                                <p class="text-gray-800">{{ $event->description }}</p>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <p class="text-gray-600">
                                        <span class="font-semibold">{{ __('Fecha de inicio') }}:</span><br>
                                        <span class="flex items-center mt-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $event->start_date->format('d/m/Y H:i') }}
                                        </span>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-600">
                                        <span class="font-semibold">{{ __('Fecha de fin') }}:</span><br>
                                        <span class="flex items-center mt-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $event->end_date->format('d/m/Y H:i') }}
                                        </span>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-600">
                                        <span class="font-semibold">{{ __('Ubicación') }}:</span><br>
                                        <span class="flex items-center mt-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            {{ $event->location }}
                                        </span>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-600">
                                        <span class="font-semibold">{{ __('Categorías') }}:</span><br>
                                        <span class="flex items-center mt-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                            </svg>
                                            {{ $event->categories->pluck('description')->implode(', ') }}
                                        </span>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-600">
                                        <span class="font-semibold">{{ __('Creado por') }}:</span><br>
                                        <span class="flex items-center mt-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            {{ $event->institution->name }} - {{ $event->user->user_name }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-gray-600 mb-2">
                                    <span class="font-semibold">{{ __('Enlace público') }}:</span>
                                </p>
                                <div class="flex items-center">
                                    <input type="text" value="{{ $eventUrl }}" readonly 
                                        class="block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        onclick="this.select()">
                                    <button type="button" onclick="copyToClipboard('{{ $eventUrl }}')" 
                                        class="ml-2 px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold mb-4">{{ __('Código QR') }}</h3>
                            <div class="bg-white p-4 rounded-lg border text-center">
                                <img src="{{ $qrCodeUrl }}" alt="QR Code" class="mx-auto max-w-full h-auto">
                                <a href="{{ $qrCodeUrl }}" download="evento-{{ $event->slug }}.svg" class="mt-4 inline-block px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                                    {{ __('Descargar QR') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row gap-2 justify-between mb-4">
                        <h3 class="text-lg font-semibold">{{ __('Participantes') }} ({{ $participants->total() }})</h3>
                        <div class="flex space-x-2 flex-wrap gap-2">
                            <a href="{{ route('events.participants.index', $event) }}" class="px-4 w-full md:w-max py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                {{ __('Ver todos') }}
                            </a>
                            <button id="export-participants-btn" class="px-4 w-full md:w-max py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-left" style="margin-left: 0px;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                {{ __('Exportar') }}
                            </button>
                        </div>
                    </div>
                    
                    <!-- Barra de búsqueda -->
                    <div class="mb-4">
                        <form action="{{ route('events.show', $event) }}" method="GET" class="flex items-center">
                            <div class="relative flex-grow">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" name="search" placeholder="{{ __('Buscar por nombre, identificación, email o teléfono...') }}" value="{{ request('search') }}" 
                                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <button type="submit" class="ml-2 flex w-max px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 whitespace-nowrap">
                                {{ __('Buscar') }}
                            </button>
                            @if(request('search'))
                                <a href="{{ route('events.show', $event) }}" class="ml-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                                    {{ __('Limpiar') }}
                                </a>
                            @endif
                        </form>
                    </div>
                    
                    @if ($participants->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Nombre') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Identificacion') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Email') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Teléfono') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Asistencia') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Acciones') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($participants as $participant)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $participant->personalData->name }} {{ $participant->personalData->last_name }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">
                                                    {{ $participant->personalData->type_dni }}: {{ $participant->personalData->dni }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">
                                                    {{ $participant->personalData->email ?? __('No registrado') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">
                                                    {{ $participant->personalData->phone }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if ($participant->assist)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        {{ __('Confirmado') }}
                                                    </span>
                                                @else
                                                    <form action="{{ route('events.participants.attendance', [$event, $participant]) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-indigo-600 hover:text-indigo-900">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                            {{ __('Confirmar') }}
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('events.participants.show', [$event, $participant]) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    {{ __('Ver') }}
                                                </a>
                                                <form action="{{ route('events.participants.destroy', [$event, $participant]) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('¿Estás seguro de eliminar este participante?')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        {{ __('Eliminar') }}
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $participants->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            @if(request('search'))
                                <p class="text-gray-500">{{ __('No se encontraron participantes que coincidan con la búsqueda: ') }} <strong>"{{ request('search') }}"</strong></p>
                                <div class="mt-2">
                                    <a href="{{ route('events.show', $event) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                        </svg>
                                        {{ __('Volver a todos los participantes') }}
                                    </a>
                                </div>
                            @else
                                <p class="text-gray-500">{{ __('No hay participantes registrados para este evento.') }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="mt-6 flex justify-between">
                <a href="{{ route('events.index') }}" class="text-gray-600 hover:text-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    {{ __('Volver a eventos') }}
                </a>
                
                <form action="{{ route('events.destroy', $event) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este evento? Esta acción no se puede deshacer.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        {{ __('Eliminar Evento') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Modal de exportación de Excel -->
    <div id="excel-export-modal" class="fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex items-center justify-center hidden">
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
    
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('Enlace copiado al portapapeles');
            }, function(err) {
                console.error('Error al copiar: ', err);
            });
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            const exportBtn = document.getElementById('export-participants-btn');
            if (exportBtn) {
                exportBtn.addEventListener('click', function() {
                    // Mostrar la modal
                    const excelModal = document.getElementById('excel-export-modal');
                    excelModal.classList.remove('hidden');
                    
                    // Redirigir a la URL de exportación
                    window.location.href = "{{ route('events.participants.export', $event) }}";
                    
                    // Configurar la funcionalidad para cerrar la modal
                    const closeModal = function() {
                        excelModal.classList.add('hidden');
                    };
                    
                    // El evento 'focus' se activará cuando el navegador vuelva a la ventana después de la descarga
                    window.addEventListener('focus', closeModal, { once: true });
                    
                    // También establecer un tiempo máximo para cerrar la modal (30 segundos)
                    setTimeout(closeModal, 30000);
                    
                    // Configurar el botón para cerrar la modal
                    document.getElementById('close-excel-modal').addEventListener('click', closeModal);
                });
            }
        });
    </script>
</x-app-layout> 
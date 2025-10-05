<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ __('Detalles de Participante') }}
        </h2>
            @if(isset($fromEvent) && $fromEvent)
                <a href="{{ route('events.participants.index', $event) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    {{ __('Volver al evento') }}
                </a>
            @else
                <a href="{{ route('participants.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    {{ __('Volver al listado') }}
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 ">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Información Personal -->
                <div class="col-span-1">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                {{ __('Información Personal') }}
                            </h3>
                            
                            <div class="mb-6 flex justify-center">
                                <div class="h-24 w-24 flex items-center justify-center bg-indigo-100 rounded-full">
                                    <span class="text-indigo-800 text-2xl font-medium">
                                        @if(isset($personalData))
                                            {{ strtoupper(substr($personalData->name, 0, 1) . substr($personalData->last_name, 0, 1)) }}
                                        @else
                                            {{ strtoupper(substr($participant->name, 0, 1) . substr($participant->last_name, 0, 1)) }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">{{ __('Nombre Completo') }}</h4>
                                <p class="text-lg font-medium">
                                    @if(isset($personalData))
                                        {{ $personalData->name }} {{ $personalData->last_name }}
                                    @else
                                        {{ $participant->name }} {{ $participant->last_name }}
                                    @endif
                                </p>
                            </div>
                            
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">{{ __('Documento') }}</h4>
                                <p>
                                    @if(isset($personalData))
                                        {{ $personalData->type_dni ?? '' }} {{ $personalData->dni ?? __('No registrado') }}
                                    @else
                                        {{ $participant->type_dni ?? '' }} {{ $participant->dni ?? __('No registrado') }}
                                    @endif
                                </p>
                            </div>
                            
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">{{ __('Fecha de Nacimiento') }}</h4>
                                <p>
                                    @if(isset($personalData) && $personalData->birth_date)
                                        {{ $personalData->birth_date->format('d/m/Y') }}
                                    @else
                                        {{ $participant->birth_date ? \Carbon\Carbon::parse($participant->birth_date)->format('d/m/Y') : __('No registrada') }}
                                    @endif
                                </p>
                            </div>
                            
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">{{ __('Edad') }}</h4>
                                <p>
                                    @if(isset($personalData) && $personalData->birth_date)
                                        {{ $personalData->age }} {{ __('años') }}
                                    @else
                                        {{ $participant->birth_date ? \Carbon\Carbon::parse($participant->birth_date)->age . ' ' . __('años') : __('No registrada') }}
                                    @endif
                                </p>
                            </div>
                            
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">{{ __('Género') }}</h4>
                                <p>
                                    @if(isset($personalData))
                                        {{ $personalData->sex ?? __('No registrado') }}
                                    @else
                                        {{ $participant->sex ?? __('No registrado') }}
                                    @endif
                                </p>
                            </div>
                            
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">{{ __('Teléfono') }}</h4>
                                <p>
                                    @if(isset($personalData))
                                        {{ $personalData->phone ?? __('No registrado') }}
                                    @else
                                        {{ $participant->phone ?? __('No registrado') }}
                                    @endif
                                </p>
                            </div>
                            
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">{{ __('Email') }}</h4>
                                <p>
                                    @if(isset($personalData))
                                        {{ $personalData->email ?? __('No registrado') }}
                                    @else
                                        {{ $participant->email ?? __('No registrado') }}
                                    @endif
                                </p>
                            </div>
                            </div>
                        </div>
                    </div>
                    
                <!-- Resumen de Participación -->
                <div class="col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                {{ __('Resumen de Participación') }}
                            </h3>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <h4 class="text-sm font-medium text-blue-700">{{ __('Total de Eventos') }}</h4>
                                    <p class="text-2xl font-bold">
                                        @if(isset($personalData))
                                            {{ count($personalData->participants) }}
                                        @else
                                            {{ count($participant->participants) }}
                                        @endif
                                </p>
                            </div>
                                
                                <div class="bg-green-50 p-4 rounded-lg">
                                    <h4 class="text-sm font-medium text-green-700">{{ __('Asistencias Confirmadas') }}</h4>
                                    <p class="text-2xl font-bold">
                                        @if(isset($personalData))
                                            {{ $personalData->participants->where('attendance', true)->count() }}
                                        @else
                                            {{ $participant->participants->where('attendance', true)->count() }}
                                        @endif
                                </p>
                            </div>
                                
                                <div class="bg-yellow-50 p-4 rounded-lg">
                                    <h4 class="text-sm font-medium text-yellow-700">{{ __('Tasa de Asistencia') }}</h4>
                                    <p class="text-2xl font-bold">
                                        @if(isset($personalData))
                                            @if(count($personalData->participants) > 0)
                                                {{ round(($personalData->participants->where('attendance', true)->count() / count($personalData->participants)) * 100) }}%
                                            @else
                                                0%
                                            @endif
                                        @else
                                            @if(count($participant->participants) > 0)
                                                {{ round(($participant->participants->where('attendance', true)->count() / count($participant->participants)) * 100) }}%
                                            @else
                                                0%
                                            @endif
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Historial de Eventos -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ __('Historial de Eventos') }}
                            </h3>
                            
                            @if(isset($personalData) && $personalData->participants->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Evento') }}
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Fecha') }}
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Organización') }}
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Estado') }}
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Acciones') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($personalData->participants as $participation)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $participation->event->name }}
                                                        </div>
                                                        <div class="text-xs text-gray-500">
                                                            {{ Str::limit($participation->event->description, 50) }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900">
                                                            {{ \Carbon\Carbon::parse($participation->event->start_date)->format('d/m/Y') }}
                                                        </div>
                                                        <div class="text-xs text-gray-500">
                                                            {{ \Carbon\Carbon::parse($participation->event->start_date)->format('H:i') }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900">
                                                            {{ $participation->event->institution->name ?? 'N/A' }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        @if($participation->attendance)
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                                {{ __('Asistió') }}
                                                            </span>
                                                        @else
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                                {{ __('Registrado') }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <a href="{{ route('events.show', $participation->event) }}" class="text-indigo-600 hover:text-indigo-900">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                            {{ __('Ver Evento') }}
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @elseif($participant->participants->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Evento') }}
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Fecha') }}
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Organización') }}
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Estado') }}
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Acciones') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($participant->participants as $participation)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $participation->event->name }}
                                                        </div>
                                                        <div class="text-xs text-gray-500">
                                                            {{ Str::limit($participation->event->description, 50) }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900">
                                                            {{ \Carbon\Carbon::parse($participation->event->start_date)->format('d/m/Y') }}
                                                        </div>
                                                        <div class="text-xs text-gray-500">
                                                            {{ \Carbon\Carbon::parse($participation->event->start_date)->format('H:i') }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900">
                                                            {{ $participation->event->institution->name ?? 'N/A' }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        @if($participation->attendance)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                                {{ __('Asistió') }}
                                                            </span>
                                                        @else
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                                {{ __('Registrado') }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <a href="{{ route('events.show', $participation->event) }}" class="text-indigo-600 hover:text-indigo-900">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                            {{ __('Ver Evento') }}
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4 text-gray-500">
                                    {{ __('No hay registros de participación en eventos.') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    @if(isset($fromEvent) && $fromEvent)
                    <!-- Acciones específicas del evento -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ __('Acciones para este evento') }}
                            </h3>
                            
                            <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                                <h4 class="text-sm font-medium text-gray-500 mb-3">{{ __('Estado de Asistencia en') }} <strong>{{ $event->name }}</strong></h4>
                                @if($participant->attendance)
                                    <div class="flex items-center mb-4">
                                        <span class="px-3 py-1 text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800 mr-2">
                                            {{ __('Asistencia Confirmada') }}
                                        </span>
                                    </div>
                                    @else
                                    <div class="flex items-center mb-4">
                                        <span class="px-3 py-1 text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 mr-2">
                                            {{ __('Registrado - Sin Confirmar Asistencia') }}
                                        </span>
                                    </div>
                                    <div class="mt-4">
                                        <form action="{{ route('events.participants.attendance', [$event, $participant]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                {{ __('Confirmar Asistencia') }}
                                            </button>
                                        </form>
                                    </div>
                                    @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
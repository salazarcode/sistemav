<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                {{ __('Usuario') }}: {{ $supervisedUser->user_name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('supervised-users.events', $supervisedUser) }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ __('Ver Eventos') }}
                </a>
                
                @if ($supervisedUser->parent_id === Auth::id())
                <a href="{{ route('supervised-users.edit', $supervisedUser) }}" class="flex w-max px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 whitespace-nowrap flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    {{ __('Editar Usuario') }}
                </a>
                @else
                <span class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ __('Supervisado indirecto (solo lectura)') }}
                </span>
                @endif
            </div>
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

            <!-- Información de Acceso -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 hover:shadow-md transition-shadow duration-300">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 flex items-center text-indigo-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                        {{ __('Información de Acceso') }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-600">
                                <span class="font-semibold">{{ __('Nombre de usuario') }}:</span><br>
                                {{ $supervisedUser->user_name }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-600">
                                <span class="font-semibold">{{ __('Email') }}:</span><br>
                                {{ $supervisedUser->email }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-600">
                                <span class="font-semibold">{{ __('Supervisor Directo') }}:</span><br>
                                @if ($supervisedUser->parent)
                                    {{ $supervisedUser->parent->user_name }}
                                @else
                                    {{ __('Sin supervisor (Usuario raíz)') }}
                                @endif
                            </p>
                        </div>
                        
                        <!-- Mostrar contraseña según permisos -->
                        <div>
                            <p class="text-gray-600">
                                <span class="font-semibold flex items-center">
                                    {{ __('Contraseña') }}
                                    <span class="ml-2 text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">{{ __('Información sensible') }}</span>
                                </span>
                                <div class="mt-2 relative">
                                    <div class="flex items-center space-x-2">
                                        <div class="relative flex-1 max-w-xs">
                                            <input 
                                                type="password" 
                                                id="password-display" 
                                                class="w-full pl-3 pr-10 py-2 border border-gray-300 rounded-md bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 cursor-default"
                                                readonly
                                                value="••••••••"
                                            >
                                            <button 
                                                type="button" 
                                                id="toggle-password" 
                                                class="absolute inset-y-0 right-0 px-2 flex items-center text-gray-600 hover:text-indigo-600 transition-colors duration-200"
                                                title="{{ __('Mostrar/Ocultar contraseña') }}"
                                                @if (!($supervisedUser->parent_id === Auth::id() || Auth::user()->hasRole('Master')))
                                                    disabled
                                                    style="opacity: 0.5; cursor: not-allowed;"
                                                @endif
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                        </div>
                                        <button 
                                            type="button" 
                                            id="copy-password" 
                                            class="p-2 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-colors duration-200 hidden"
                                            title="{{ __('Copiar contraseña') }}"
                                            @if (!($supervisedUser->parent_id === Auth::id() || Auth::user()->hasRole('Master')))
                                                disabled
                                                style="opacity: 0.5; cursor: not-allowed;"
                                            @endif
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                            </svg>
                                        </button>
                                        
                                        <!-- Botón para cambiar contraseña -->
                                        @if ($supervisedUser->parent_id === Auth::id() || (Auth::user()->hasRole('Master') && Auth::id() === $supervisedUser->id))
                                        <button 
                                            type="button" 
                                            id="change-password-btn" 
                                            class="p-2 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-md transition-colors duration-200 flex items-center"
                                            title="{{ __('Cambiar contraseña') }}"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                            {{ __('Cambiar') }}
                                        </button>
                                        @endif
                                    </div>
                                    <div id="password-message" class="hidden mt-2 text-sm text-gray-500 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span></span>
                                    </div>
                                </div>
                                <span class="text-sm text-gray-500 block mt-2 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    @if ($supervisedUser->parent_id === Auth::id())
                                        {{ __('Nota: La contraseña se muestra solo para ti como supervisor directo.') }}
                                    @elseif (Auth::user()->hasRole('Master'))
                                        {{ __('Nota: Como usuario Master, puedes ver todas las contraseñas pero solo puedes cambiar las de tus supervisados directos.') }}
                                    @else
                                        {{ __('Nota: Solo los supervisores directos pueden ver y cambiar las contraseñas.') }}
                                    @endif
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Información Personal -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 hover:shadow-md transition-shadow duration-300">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 flex items-center text-indigo-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ __('Información Personal') }}
                    </h3>
                    
                    @if ($personalData)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-600">
                                    <span class="font-semibold">{{ __('Nombre completo') }}:</span><br>
                                    {{ $personalData->name }} {{ $personalData->last_name }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-600">
                                    <span class="font-semibold">{{ __('Identificación') }}:</span><br>
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
                            <div>
                                <p class="text-gray-600">
                                    <span class="font-semibold">{{ __('Institución') }}:</span><br>
                                    {{ $supervisedUser->institution->name }}
                                </p>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-500">{{ __('No hay datos personales disponibles.') }}</p>
                    @endif
                </div>
            </div>
            
            <!-- Roles y Permisos -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 hover:shadow-md transition-shadow duration-300">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 flex items-center text-indigo-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        {{ __('Roles y Permisos') }}
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium mb-3 flex items-center text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                {{ __('Roles') }}
                            </h4>
                            @if ($roles->count() > 0)
                                <div class="space-y-2">
                                    @foreach ($roles as $role)
                                        <div class="flex items-center bg-white p-2 rounded-md shadow-sm">
                                            <span class="w-2 h-2 bg-indigo-500 rounded-full mr-2"></span>
                                            <span>{{ $role->name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500">{{ __('No tiene roles asignados.') }}</p>
                            @endif
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium mb-3 flex items-center text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                </svg>
                                {{ __('Permisos') }}
                            </h4>
                            @if ($permissions->count() > 0)
                                <div class="space-y-2">
                                    @forelse ($permissions as $permission)
                                        <div class="flex items-center">
                                            <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800">
                                                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ \App\Helpers\PermissionHelper::getFriendlyName($permission->name) }}
                                            </span>
                                            <span class="ml-2 text-xs text-gray-500">{{ \App\Helpers\PermissionHelper::getDescription($permission->name) }}</span>
                                        </div>
                                    @empty
                                        <p class="text-gray-500 text-sm">{{ __('No tiene permisos asignados.') }}</p>
                                    @endforelse
                                </div>
                            @else
                                <p class="text-gray-500">{{ __('No tiene permisos asignados.') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Supervisados Directos -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 hover:shadow-md transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                        <h3 class="text-lg font-semibold flex items-center text-indigo-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            {{ __('Supervisados') }}
                        </h3>
                        <div class="mt-4 md:mt-0 flex flex-wrap gap-4">
                            <!-- Filtros -->
                            <div class="w-full flex flex-col md:flex-row gap-4">
                                <!-- Filtro de tipo de supervisados -->
                                <div class="w-full md:w-48">
                                    <label for="supervisee-type" class="block text-sm font-medium text-gray-700 mb-1">
                                        {{ __('Tipo de supervisión') }}
                                    </label>
                                    <select id="supervisee-type" class="w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                        <option value="direct">{{ __('Supervisados Directos') }}</option>
                                        <option value="all">{{ __('Todos los Supervisados') }}</option>
                                    </select>
                                </div>
                                
                                <!-- Barra de búsqueda -->
                                <div class="w-full md:w-64">
                                    <label for="search-supervisees" class="block text-sm font-medium text-gray-700 mb-1">
                                        {{ __('Buscar por nombre o email') }}
                                    </label>
                                    <div class="relative">
                                        <input type="text" 
                                            id="search-supervisees" 
                                            class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                            placeholder="{{ __('Buscar supervisados...') }}">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botón de aplicar filtros -->
                                <div class="w-full md:w-auto flex items-end">
                                    <button type="button" 
                                            id="apply-filters"
                                            class="w-full md:w-auto flex w-max px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                        </svg>
                                        {{ __('Aplicar Filtros') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lista de supervisados -->
                    <div id="supervisees-list" class="space-y-2">
                        @if($supervisedUser->supervisedUsers->isNotEmpty())
                            @foreach($supervisedUser->all_supervised_users ?? collect() as $supervisee)
                                <div class="supervisee-item bg-gray-50 p-3 rounded-lg flex items-center justify-between hover:bg-gray-100 transition-colors duration-200"
                                     data-type="{{ $supervisee->parent_id === $supervisedUser->id ? 'direct' : 'indirect' }}"
                                     data-name="{{ $supervisee->user_name }}"
                                     data-email="{{ $supervisee->email }}">
                                    <div class="flex items-center flex-1">
                                        <div class="flex-shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <span class="font-medium">{{ $supervisee->user_name }}</span>
                                                    <span class="text-sm text-gray-500 ml-2">{{ $supervisee->email }}</span>
                                                </div>
                                                @if($supervisee->parent_id !== $supervisedUser->id)
                                                    <span class="text-xs bg-gray-200 text-gray-600 px-2 py-1 rounded-full ml-2">
                                                        {{ __('Indirecto') }}
                                                    </span>
                                                @endif
                                            </div>
                                            @if($supervisee->parent_id !== $supervisedUser->id)
                                                <div class="text-xs text-gray-500 mt-1">
                                                    {{ __('A través de:') }} {{ $supervisee->parent->user_name }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-shrink-0">
                                        <a href="{{ route('supervised-users.show', $supervisee) }}" class="text-indigo-600 hover:text-indigo-900">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-500 text-center py-4">{{ __('Este usuario no tiene supervisados.') }}</p>
                        @endif
                    </div>
                    <div id="no-results" class="hidden">
                        <p class="text-gray-500 text-center py-4">{{ __('No se encontraron supervisados.') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-between">
                <a href="{{ route('supervised-users.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ __('Volver a la lista de usuarios') }}
                </a>
                
                @if ($supervisedUser->parent_id === Auth::id())
                <form action="{{ route('supervised-users.destroy', $supervisedUser) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        {{ __('Eliminar Usuario') }}
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Modal para cambiar contraseña -->
    <div id="change-password-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="flex flex-col">
                <div class="flex justify-between items-center border-b pb-3">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Cambiar Contraseña') }}</h3>
                    <button id="close-password-modal" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="mt-4">
                    <form method="post" action="{{ route('supervised-users.update-password', $supervisedUser) }}" class="space-y-6">
                        @csrf
                        @method('patch')

                        <div>
                            <x-input-label for="password" :value="__('Nueva Contraseña')" />
                            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required />
                            <x-input-error class="mt-2" :messages="$errors->get('password')" />
                        </div>

                        <div>
                            <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />
                            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" required />
                            <x-input-error class="mt-2" :messages="$errors->get('password_confirmation')" />
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="button" id="cancel-password-change" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 mr-2">
                                {{ __('Cancelar') }}
                            </button>
                            <x-primary-button>{{ __('Cambiar Contraseña') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elementos para mostrar/ocultar contraseña
            const togglePasswordBtn = document.getElementById('toggle-password');
            const passwordDisplay = document.getElementById('password-display');
            const copyPasswordBtn = document.getElementById('copy-password');
            const passwordMessage = document.getElementById('password-message');
            const passwordMessageText = passwordMessage.querySelector('span');
            
            // Verificar si el usuario tiene permiso para ver la contraseña
            const canViewPassword = {{ ($supervisedUser->parent_id === Auth::id() || Auth::user()->hasRole('Master')) ? 'true' : 'false' }};
            
            if (togglePasswordBtn && passwordDisplay && canViewPassword) {
                let passwordVisible = false;
                let passwordValue = '';
                
                // Función para obtener la contraseña
                const fetchPassword = async () => {
                    try {
                        const response = await fetch(`{{ route('supervised-users.get-password', $supervisedUser) }}`, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            credentials: 'same-origin'
                        });
                        
                        if (!response.ok) {
                            throw new Error('No se pudo obtener la contraseña');
                        }
                        
                        const data = await response.json();
                        
                        // Mostrar mensaje si existe
                        if (data.message) {
                            passwordMessageText.textContent = data.message;
                            passwordMessage.classList.remove('hidden');
                            setTimeout(() => {
                                passwordMessage.classList.add('hidden');
                            }, 5000);
                        }
                        
                        return data.password;
                    } catch (error) {
                        console.error('Error:', error);
                        return null;
                    }
                };
                
                // Mostrar/ocultar contraseña
                togglePasswordBtn.addEventListener('click', async function() {
                    if (!passwordVisible) {
                        // Si no tenemos la contraseña, la obtenemos
                        if (!passwordValue) {
                            passwordDisplay.value = 'Cargando...';
                            passwordValue = await fetchPassword();
                            
                            if (!passwordValue) {
                                passwordDisplay.value = '••••••••';
                                passwordMessageText.textContent = 'Error al obtener la contraseña. Inténtalo de nuevo.';
                                passwordMessage.classList.remove('hidden');
                                setTimeout(() => {
                                    passwordMessage.classList.add('hidden');
                                }, 3000);
                                return;
                            }
                        }
                        
                        passwordDisplay.type = 'text';
                        passwordDisplay.value = passwordValue;
                        togglePasswordBtn.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        `;
                        copyPasswordBtn.classList.remove('hidden');
                        passwordVisible = true;
                    } else {
                        passwordDisplay.type = 'password';
                        passwordDisplay.value = '••••••••';
                        togglePasswordBtn.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        `;
                        copyPasswordBtn.classList.add('hidden');
                        passwordVisible = false;
                    }
                });
                
                // Copiar contraseña
                if (copyPasswordBtn) {
                    copyPasswordBtn.addEventListener('click', function() {
                        if (passwordValue) {
                            navigator.clipboard.writeText(passwordValue).then(function() {
                                passwordMessageText.textContent = 'Contraseña copiada al portapapeles';
                                passwordMessage.classList.remove('hidden');
                                setTimeout(() => {
                                    passwordMessage.classList.add('hidden');
                                }, 3000);
                            }, function() {
                                passwordMessageText.textContent = 'Error al copiar la contraseña';
                                passwordMessage.classList.remove('hidden');
                                setTimeout(() => {
                                    passwordMessage.classList.add('hidden');
                                }, 3000);
                            });
                        }
                    });
                }
            }
            
            // Resto del código para filtros y supervisados
            // ... existing code ...
            
            // Modal de cambio de contraseña
            const passwordModal = document.getElementById('change-password-modal');
            const openPasswordModalBtn = document.getElementById('change-password-btn');
            const closePasswordModalBtn = document.getElementById('close-password-modal');
            const cancelPasswordBtn = document.getElementById('cancel-password-change');

            if (openPasswordModalBtn) {
                openPasswordModalBtn.addEventListener('click', function() {
                    passwordModal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                });
            }

            const closePasswordModal = function() {
                passwordModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            };

            if (closePasswordModalBtn) {
                closePasswordModalBtn.addEventListener('click', closePasswordModal);
            }
            
            if (cancelPasswordBtn) {
                cancelPasswordBtn.addEventListener('click', closePasswordModal);
            }

            // Cerrar modal al hacer clic fuera del contenido
            passwordModal.addEventListener('click', function(e) {
                if (e.target === passwordModal) {
                    closePasswordModal();
                }
            });

            // Mostrar mensaje de éxito
            @if (session('status') === 'password-updated')
                const successMessage = document.createElement('div');
                successMessage.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50 flex items-center';
                successMessage.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ __('Contraseña actualizada correctamente.') }}
                `;
                document.body.appendChild(successMessage);
                
                setTimeout(() => {
                    successMessage.remove();
                }, 3000);
            @endif
        });
    </script>
    @endpush
</x-app-layout> 
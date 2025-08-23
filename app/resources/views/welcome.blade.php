<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="min-h-screen flex flex-col">
            <!-- Header -->
            <header class="shadow" style="background-color: #f7f7f7;">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center">
                        <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                            <img src="{{ asset('images/logos/sistema_victoria--large-logo.jpg') }}" alt="Sistema Victoria" class="h-12 mr-2" />
                        </h1>
                        <div>
            @if (Route::has('login'))
                                <div class="space-x-4">
                    @auth
                                        <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-gray-300 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-indigo-500">
                                            {{ __('Panel de Control') }}
                                        </a>
                    @else
                                        <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-indigo-500">
                                            {{ __('Iniciar Sesión') }}
                                        </a>

                        @if (Route::has('register') && !\App\Models\User::whereHas('roles', function($query) { $query->where('name', 'master'); })->exists())
                                            <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-indigo-500">
                                                {{ __('Registrarse') }}
                                            </a>
                        @endif
                    @endauth
                </div>
            @endif
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-grow">
                <div class="py-12">
                    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 ">
                        <!-- Filters -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                            <div class="p-6">
                                <h2 class="text-xl font-semibold mb-4 text-gray-800">{{ __('Próximos Eventos') }}</h2>
                                
                                <form action="{{ route('welcome') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                                    <div class="flex-1">
                                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Buscar por nombre o descripción') }}</label>
                                        <div class="relative">
                                            <input type="text" 
                                                id="search" 
                                                name="search" 
                                                value="{{ request('search') }}" 
                                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                                placeholder="{{ __('Buscar eventos...') }}">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                            </div>
                                        </div>
                                </div>

                                    <div class="w-full md:w-48">
                                        <label for="per_page" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Eventos por página') }}</label>
                                        <select id="per_page" name="per_page" class="w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                            @foreach($perPageOptions as $option)
                                                <option value="{{ $option }}" {{ request('per_page') == $option ? 'selected' : '' }}>{{ $option }}</option>
                                            @endforeach
                                        </select>
                            </div>

                                    <div class="flex items-end">
                                        <button type="submit" class="w-full md:w-auto flex w-max px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                            </svg>
                                            {{ __('Filtrar') }}
                                        </button>
                                        <a href="{{ route('welcome') }}" class="ml-2 w-full md:w-auto px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                                            {{ __('Limpiar') }}
                                        </a>
                                    </div>
                                </form>
                                </div>
                            </div>

                        <!-- Events List -->
                        @if($events->count() > 0)
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Evento') }}
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Fecha de Inicio') }}
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Organización') }}
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Categorías') }}
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Acciones') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($events as $event)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ $event->name }}
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900">
                                                            {{ $event->start_date->format('d/m/Y') }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $event->start_date->format('H:i') }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900">
                                                            {{ $event->institution->name }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <div class="flex flex-wrap gap-1">
                                                            @foreach($event->categories as $category)
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800">
                                                                    {{ $category->description }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <a href="{{ route('events.public.show', $event->slug) }}" class="text-indigo-600 hover:text-indigo-900">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                                                            {{ __('Ver detalles') }}
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Pagination -->
                            <div class="mt-6">
                                {{ $events->appends(['search' => request('search'), 'per_page' => request('per_page', config('app.pagination.per_page', 10))])->links() }}
                            </div>
                        @else
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('No se encontraron eventos') }}</h3>
                                    <p class="text-gray-500">{{ __('No hay eventos disponibles que coincidan con tu búsqueda.') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </main>
            
            <!-- Footer -->
            @include('layouts.footer')
        </div>
    </body>
</html>

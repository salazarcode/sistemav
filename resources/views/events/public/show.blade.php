<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $event->name }} - {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-gray-900">
                        {{ $event->name }}
                    </h1>
                    <div>
                        <a href="{{ url('/') }}" class="text-indigo-600 hover:text-indigo-800">
                            {{ config('app.name') }}
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main>
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
                        <!-- Event Details -->
                        <div class="md:col-span-2">
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                                <div class="p-6">
                                    @if ($event->img)
                                        <div class="mb-6">
                                            <img src="{{ Storage::url($event->img) }}" alt="{{ $event->name }}" class="w-full h-64 object-cover rounded-lg">
                                        </div>
                                    @endif

                                    <h2 class="text-xl font-semibold mb-4">{{ __('Detalles del Evento') }}</h2>
                                    
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
                                                {{ $event->start_date->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-gray-600">
                                                <span class="font-semibold">{{ __('Fecha de fin') }}:</span><br>
                                                {{ $event->end_date->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-gray-600">
                                                <span class="font-semibold">{{ __('Ubicación') }}:</span><br>
                                                {{ $event->location }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-gray-600">
                                                <span class="font-semibold">{{ __('Categorías') }}:</span><br>
                                                {{ $event->categories->pluck('description')->implode(', ') }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-6">
                                        <img src="{{ $qrCodeUrl }}" alt="QR Code" class="h-32 mx-auto">
                                        <p class="text-center text-sm text-gray-500 mt-2">{{ __('Escanea este código QR para compartir el evento') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Registration Form -->
                        <div class="md:col-span-1">
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6">
                                    <h2 class="text-xl font-semibold mb-4">{{ __('Registro') }}</h2>
                                    
                                    <form action="{{ route('events.public.register', $event->slug) }}" method="POST">
                                        @csrf
                                        
                                        <!-- Type DNI -->
                                        <div class="mb-6">
                                            <label for="type_dni" class="block text-sm font-medium text-gray-700">{{ __('Tipo de Identificación:') }}</label>
                                            <select id="type_dni" name="type_dni" required
                                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                                <option value="">{{ __('Seleccionar...') }}</option>
                                                <option value="V" {{ old('type_dni') == 'V' ? 'selected' : '' }}>{{ __('V') }}</option>
                                                <option value="E" {{ old('type_dni') == 'E' ? 'selected' : '' }}>{{ __('E') }}</option>
                                                <option value="J" {{ old('type_dni') == 'J' ? 'selected' : '' }}>{{ __('J') }}</option>
                                                <option value="G" {{ old('type_dni') == 'G' ? 'selected' : '' }}>{{ __('G') }}</option>
                                                <option value="P" {{ old('type_dni') == 'P' ? 'selected' : '' }}>{{ __('P') }}</option>
                                            </select>
                                            @error('type_dni')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <!-- DNI -->
                                        <div class="mb-4">
                                            <label for="dni" class="block text-sm font-medium text-gray-700">{{ __('Identificación:') }}</label>
                                            <input type="text" name="dni" id="dni" value="{{ old('dni') }}" required
                                                class="mt-1 block w-full p-2 border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                                                placeholder="{{ __('Ingresa tu número de identificación') }}">
                                            @error('dni')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <!-- Name -->
                                        <div class="mb-4">
                                            <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Nombre:') }}</label>
                                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            @error('name')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <!-- Last Name -->
                                        <div class="mb-4">
                                            <label for="last_name" class="block text-sm font-medium text-gray-700">{{ __('Apellido:') }}</label>
                                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            @error('last_name')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <!-- Phone -->
                                        <div class="mb-4">
                                            <label for="phone" class="block text-sm font-medium text-gray-700">{{ __('Teléfono:') }}</label>
                                            <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            @error('phone')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <!-- Email -->
                                        <div class="mb-4">
                                            <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Correo Electrónico:') }}</label>
                                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            @error('email')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        
                                        <!-- Gender -->
                                        <div class="mb-4">
                                            <label for="sex" class="block text-sm font-medium text-gray-700">{{ __('Género:') }}</label>
                                            <select id="sex" name="sex" required
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="">{{ __('Seleccionar') }}</option>
                                                <option value="M" {{ old('sex') == 'M' ? 'selected' : '' }}>{{ __('Masculino') }}</option>
                                                <option value="F" {{ old('sex') == 'F' ? 'selected' : '' }}>{{ __('Femenino') }}</option>
                                                <option value="O" {{ old('sex') == 'O' ? 'selected' : '' }}>{{ __('Otro') }}</option>
                                            </select>
                                            @error('sex')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <!-- Birth Date -->
                                        <div class="mb-4">
                                            <label for="birth_date" class="block text-sm font-medium text-gray-700">{{ __('Fecha de Nacimiento:') }}</label>
                                            <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required max="{{ date('Y-m-d') }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            @error('birth_date')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>                   
                                        
                                        <div>
                                            <button type="submit" class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                {{ __('Registrarme') }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        
        <!-- Footer -->
        <footer class="bg-white shadow-inner mt-8">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <p class="text-center text-sm text-gray-500">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('Todos los derechos reservados.') }}
                </p>
            </div>
        </footer>
    </div>
</body>
</html> 
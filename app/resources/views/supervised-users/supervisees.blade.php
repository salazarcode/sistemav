<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                {{ __('Supervisados Directos de') }}: {{ $supervisedUser->user_name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('supervised-users.show', $supervisedUser) }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ __('Volver al Perfil') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filtros y Opciones -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <!-- Búsqueda -->
                        <div class="flex-1 max-w-md">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Buscar') }}</label>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    id="search" 
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="{{ __('Buscar por nombre, email...') }}"
                                >
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Filtros -->
                        <div class="flex flex-wrap gap-4">
                            <!-- Filtro por Rol -->
                            <div class="w-full md:w-auto">
                                <label for="role-filter" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Filtrar por Rol') }}</label>
                                <select id="role-filter" class="w-full md:w-48 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">{{ __('Todos los roles') }}</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Ordenar por -->
                            <div class="w-full md:w-auto">
                                <label for="sort-by" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Ordenar por') }}</label>
                                <select id="sort-by" class="w-full md:w-48 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="name_asc">{{ __('Nombre (A-Z)') }}</option>
                                    <option value="name_desc">{{ __('Nombre (Z-A)') }}</option>
                                    <option value="created_asc">{{ __('Más antiguos') }}</option>
                                    <option value="created_desc">{{ __('Más recientes') }}</option>
                                </select>
                            </div>

                            <!-- Vista -->
                            <div class="w-full md:w-auto">
                                <label for="view-type" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Vista') }}</label>
                                <div class="flex rounded-md shadow-sm">
                                    <button type="button" id="grid-view" class="px-4 py-2 text-sm font-medium rounded-l-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                        </svg>
                                    </button>
                                    <button type="button" id="list-view" class="px-4 py-2 text-sm font-medium rounded-r-md border border-l-0 border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Supervisados -->
            <div id="supervisees-container">
                <!-- Vista de Grid -->
                <div id="grid-view-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($supervisedUser->supervisedUsers as $supervisee)
                    <div class="supervisee-item bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-300">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-medium text-gray-900">{{ $supervisee->user_name }}</h3>
                                        <p class="text-sm text-gray-500">{{ $supervisee->email }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('supervised-users.show', $supervisee) }}" class="text-indigo-600 hover:text-indigo-900" title="{{ __('Ver detalles') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($supervisee->roles as $role)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Vista de Lista -->
                <div id="list-view-container" class="hidden">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Usuario') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Email') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Roles') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Acciones') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($supervisedUser->supervisedUsers as $supervisee)
                                    <tr class="supervisee-item hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $supervisee->user_name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $supervisee->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($supervisee->roles as $role)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                        {{ $role->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('supervised-users.show', $supervisee) }}" class="text-indigo-600 hover:text-indigo-900">{{ __('Ver detalles') }}</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search');
            const roleFilter = document.getElementById('role-filter');
            const sortBy = document.getElementById('sort-by');
            const gridViewBtn = document.getElementById('grid-view');
            const listViewBtn = document.getElementById('list-view');
            const gridViewContainer = document.getElementById('grid-view-container');
            const listViewContainer = document.getElementById('list-view-container');
            const superviseeItems = document.querySelectorAll('.supervisee-item');

            // Función para filtrar elementos
            const filterItems = () => {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedRole = roleFilter.value;

                superviseeItems.forEach(item => {
                    const text = item.textContent.toLowerCase();
                    const roleMatch = !selectedRole || item.querySelector(`[data-role="${selectedRole}"]`);
                    const searchMatch = text.includes(searchTerm);

                    item.style.display = (searchMatch && roleMatch) ? '' : 'none';
                });
            };

            // Cambiar vista
            gridViewBtn.addEventListener('click', () => {
                gridViewContainer.classList.remove('hidden');
                listViewContainer.classList.add('hidden');
                gridViewBtn.classList.add('bg-gray-100');
                listViewBtn.classList.remove('bg-gray-100');
            });

            listViewBtn.addEventListener('click', () => {
                gridViewContainer.classList.add('hidden');
                listViewContainer.classList.remove('hidden');
                listViewBtn.classList.add('bg-gray-100');
                gridViewBtn.classList.remove('bg-gray-100');
            });

            // Event listeners para filtros
            searchInput.addEventListener('input', filterItems);
            roleFilter.addEventListener('change', filterItems);

            // Ordenar elementos
            sortBy.addEventListener('change', function() {
                const container = document.querySelector('#supervisees-container');
                const items = Array.from(superviseeItems);

                items.sort((a, b) => {
                    const nameA = a.querySelector('.text-lg, .text-sm.font-medium').textContent.toLowerCase();
                    const nameB = b.querySelector('.text-lg, .text-sm.font-medium').textContent.toLowerCase();

                    switch(this.value) {
                        case 'name_asc':
                            return nameA.localeCompare(nameB);
                        case 'name_desc':
                            return nameB.localeCompare(nameA);
                        // Agregar más casos de ordenamiento si es necesario
                    }
                });

                items.forEach(item => {
                    item.parentElement.appendChild(item);
                });
            });
        });
    </script>
    @endpush
</x-app-layout> 
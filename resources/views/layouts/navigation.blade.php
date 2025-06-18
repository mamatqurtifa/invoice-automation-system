<nav x-data="{ open: false, productOpen: false, notificationOpen: false }"
    class="sticky top-0 z-50 backdrop-blur-lg bg-white/80 border-b border-gray-100 shadow-sm transition-all duration-300">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="group">
                        <x-application-logo
                            class="block h-9 w-auto fill-current text-gray-800 transform transition-transform duration-300 ease-in-out group-hover:scale-110" />
                    </a>
                </div>

                <!-- Navigation Links - Desktop -->
                <div class="hidden space-x-3 sm:flex sm:items-center sm:ml-6">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                        class="flex items-center space-x-1 px-4 py-2 rounded-full transition-all duration-200 ease-in-out hover:bg-indigo-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                        </svg>
                        <span>{{ __('Dashboard') }}</span>
                    </x-nav-link>

                    <!-- Projects Dropdown Menu -->
                    <div x-data="{ open: false }" @click.away="open = false" @keydown.escape.window="open = false"
                        class="relative">
                        <button @click="open = !open"
                            class="flex items-center space-x-1 px-4 py-2 rounded-full transition-all duration-200 ease-in-out hover:bg-indigo-50 font-medium text-gray-500 hover:text-gray-700 {{ request()->routeIs('projects.*') ? 'text-indigo-600 bg-indigo-50' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M2 6a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1H8a3 3 0 00-3 3v1.5a1.5 1.5 0 01-3 0V6z"
                                    clip-rule="evenodd" />
                                <path d="M6 12a2 2 0 012-2h8a2 2 0 012 2v2a2 2 0 01-2 2H2h2a2 2 0 002-2v-2z" />
                            </svg>
                            <span>{{ __('Projects') }}</span>
                            <svg class="ml-1 h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': open }"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- Projects Dropdown Content -->
                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-1"
                            class="absolute left-0 mt-2 w-72 rounded-2xl bg-white/90 backdrop-blur-lg shadow-xl ring-1 ring-black ring-opacity-5 z-50 overflow-hidden"
                            style="display: none;">
                            <div class="p-2 space-y-1">
                                <a href="{{ route('projects.index') }}"
                                    class="block px-4 py-3 rounded-xl transition-all hover:bg-indigo-50">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-indigo-100 rounded-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path
                                                    d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ __('All Projects') }}</div>
                                            <p class="text-xs text-gray-500">
                                                {{ __('View and manage all your projects') }}</p>
                                        </div>
                                    </div>
                                </a>
                                <a href="{{ route('projects.create') }}"
                                    class="block px-4 py-3 rounded-xl transition-all hover:bg-indigo-50">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-green-100 rounded-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ __('New Project') }}</div>
                                            <p class="text-xs text-gray-500">{{ __('Create a new project') }}</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- App Modules Dropdown Menu -->
                    <div x-data="{ open: false }" @click.away="open = false" @keydown.escape.window="open = false"
                        class="relative">
                        <button @click="open = !open"
                            class="flex items-center space-x-1 px-4 py-2 rounded-full transition-all duration-200 ease-in-out hover:bg-indigo-50 font-medium text-gray-500 hover:text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                            <span>{{ __('Features') }}</span>
                            <svg class="ml-1 h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': open }"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- Features Dropdown Content -->
                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-1"
                            class="absolute -left-12 mt-2 w-80 rounded-2xl bg-white/90 backdrop-blur-lg shadow-xl ring-1 ring-black ring-opacity-5 z-50 overflow-hidden"
                            style="display: none;">
                            <div class="p-4">
                                <div class="grid grid-cols-1 gap-4">
                                    <a href="#"
                                        class="group flex items-center gap-x-5 rounded-lg p-3 hover:bg-indigo-50">
                                        <div
                                            class="flex h-12 w-12 flex-none items-center justify-center rounded-lg bg-indigo-100 group-hover:bg-indigo-200 transition-colors duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path
                                                    d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2h-1.528A6 6 0 004 9.528V4z" />
                                                <path fill-rule="evenodd"
                                                    d="M8 10a4 4 0 00-3.446 6.032l-1.261 1.26a1 1 0 101.414 1.415l1.261-1.261A4 4 0 108 10zm-2 4a2 2 0 114 0 2 2 0 01-4 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-900">Form Builder</h3>
                                            <p class="mt-1 text-xs text-gray-600">Create and manage custom order forms
                                            </p>
                                        </div>
                                    </a>
                                    <a href="#"
                                        class="group flex items-center gap-x-5 rounded-lg p-3 hover:bg-indigo-50">
                                        <div
                                            class="flex h-12 w-12 flex-none items-center justify-center rounded-lg bg-indigo-100 group-hover:bg-indigo-200 transition-colors duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-900">Products & Variants</h3>
                                            <p class="mt-1 text-xs text-gray-600">Manage product catalog with variants
                                            </p>
                                        </div>
                                    </a>
                                    <a href="#"
                                        class="group flex items-center gap-x-5 rounded-lg p-3 hover:bg-indigo-50">
                                        <div
                                            class="flex h-12 w-12 flex-none items-center justify-center rounded-lg bg-indigo-100 group-hover:bg-indigo-200 transition-colors duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-900">Invoices & Payments</h3>
                                            <p class="mt-1 text-xs text-gray-600">Generate invoices and track payments
                                            </p>
                                        </div>
                                    </a>
                                    <a href="#"
                                        class="group flex items-center gap-x-5 rounded-lg p-3 hover:bg-indigo-50">
                                        <div
                                            class="flex h-12 w-12 flex-none items-center justify-center rounded-lg bg-indigo-100 group-hover:bg-indigo-200 transition-colors duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-900">Financial Reports</h3>
                                            <p class="mt-1 text-xs text-gray-600">View analytics and export reports</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings, Notification, and Profile Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6 space-x-3">
                <!-- Help & Support -->
                <div x-data="{ open: false }" @click.away="open = false" @keydown.escape.window="open = false"
                    class="relative">
                    <button @click="open = !open"
                        class="flex items-center justify-center h-10 w-10 rounded-full transition-all duration-200 ease-in-out hover:bg-indigo-50 text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>

                    <!-- Help Dropdown -->
                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-1"
                        class="absolute right-0 z-50 mt-2 w-56 origin-top-right rounded-2xl bg-white/90 backdrop-blur-lg shadow-lg ring-1 ring-black ring-opacity-5 overflow-hidden"
                        style="display: none;">
                        <div class="py-1">
                            <a href="{{ route('user-guide') }}"
                                class="group flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-indigo-50">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="mr-3 h-5 w-5 text-gray-400 group-hover:text-indigo-500" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path
                                        d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z" />
                                </svg>
                                User Guide
                            </a>
                            <a href="{{ route('faq') }}"
                                class="group flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-indigo-50">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="mr-3 h-5 w-5 text-gray-400 group-hover:text-indigo-500" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd" />
                                </svg>
                                FAQ
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Notifications -->
                <div x-data="{ open: false }" @click.away="open = false" @keydown.escape.window="open = false"
                    class="relative">
                    <button @click="open = !open"
                        class="flex items-center justify-center h-10 w-10 rounded-full transition-all duration-200 ease-in-out hover:bg-indigo-50 text-gray-500 hover:text-gray-700 relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path
                                d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                        </svg>
                        @if (auth()->user()->unreadNotificationsCount() > 0)
                            <span
                                class="absolute top-1 right-1 transform translate-x-1/2 -translate-y-1/2 flex h-5 w-5">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span
                                    class="relative rounded-full h-5 w-5 bg-red-500 text-white text-xs flex items-center justify-center">
                                    {{ auth()->user()->unreadNotificationsCount() }}
                                </span>
                            </span>
                        @endif
                    </button>

                    <!-- Notifications Dropdown -->
                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-1"
                        class="absolute right-0 z-50 mt-2 w-80 max-h-96 overflow-y-auto origin-top-right rounded-2xl bg-white/90 backdrop-blur-lg shadow-lg ring-1 ring-black ring-opacity-5"
                        style="display: none;">
                        <div class="py-2 px-3 flex justify-between items-center border-b">
                            <h3 class="text-sm font-bold text-gray-700">Notifications</h3>
                            <a href="{{ route('notifications.index') }}"
                                class="text-xs text-indigo-600 hover:text-indigo-800">View All</a>
                        </div>
                        <div class="py-2">
                            @forelse(auth()->user()->notifications()->whereNull('read_at')->latest()->take(5)->get() as $notification)
                                <a href="{{ route('notifications.markAsRead', $notification) }}"
                                    class="flex items-start px-4 py-3 hover:bg-indigo-50 {{ $loop->first ? '' : 'border-t border-gray-100' }}">
                                    <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-indigo-500"></div>
                                    <div class="ml-3 w-full">
                                        <p class="text-sm font-medium text-gray-900">{{ $notification->title }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ $notification->message }}</p>
                                        <p class="mt-1 text-xs text-gray-400">
                                            {{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                </a>
                            @empty
                                <div class="px-4 py-6 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500">No new notifications</p>
                                </div>
                            @endforelse

                            @if (auth()->user()->notifications()->whereNull('read_at')->count() > 0)
                                <div class="mt-2 px-4 py-2 border-t border-gray-100 text-center">
                                    <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-xs text-indigo-600 hover:text-indigo-800">
                                            Mark All as Read
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Profile dropdown -->
                <div x-data="{ open: false }" @click.away="open = false" @keydown.escape.window="open = false"
                    class="relative">
                    <div>
                        <button @click="open = !open"
                            class="flex items-center transition duration-150 ease-in-out group">
                            <div
                                class="h-10 w-10 rounded-full overflow-hidden border-2 border-indigo-100 group-hover:border-indigo-200 transition-all duration-200 flex-shrink-0">
                                <img class="h-full w-full object-cover" src="{{ auth()->user()->profilePhotoUrl() }}"
                                    alt="{{ auth()->user()->name }}">
                            </div>
                            <div class="ml-2 hidden md:block">
                                <div
                                    class="text-sm font-medium text-gray-800 group-hover:text-indigo-600 transition-colors duration-200">
                                    {{ auth()->user()->name }}</div>
                                <div
                                    class="text-xs text-gray-500 group-hover:text-indigo-500 transition-colors duration-200">
                                    {{ auth()->user()->email }}</div>
                            </div>
                        </button>
                    </div>

                    <!-- Profile Dropdown Menu -->
                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-1"
                        class="absolute right-0 z-50 mt-2 w-56 origin-top-right rounded-2xl bg-white/90 backdrop-blur-lg shadow-lg ring-1 ring-black ring-opacity-5 overflow-hidden"
                        style="display: none;">
                        <div class="py-3 px-4 border-b border-gray-100">
                            <div class="font-medium text-base text-gray-800">{{ auth()->user()->name }}</div>
                            <div class="font-medium text-xs text-gray-500">{{ auth()->user()->email }}</div>
                        </div>
                        <div class="py-1">
                            <a href="{{ route('profile.edit') }}"
                                class="group flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-indigo-50">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="mr-3 h-5 w-5 text-gray-400 group-hover:text-indigo-500" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                        clip-rule="evenodd" />
                                </svg>
                                Profile Settings
                            </a>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left group flex items-center px-4 py-3 text-sm text-red-700 hover:bg-red-50">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="mr-3 h-5 w-5 text-red-400 group-hover:text-red-500" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ __('Log Out') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu, show/hide based on menu state. -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path
                        d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                </svg>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <!-- Projects -->
            <div x-data="{ expanded: false }" class="border-l-4 border-transparent pl-3">
                <button @click="expanded = !expanded"
                    class="flex items-center justify-between w-full py-2 px-3 text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M2 6a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1H8a3 3 0 00-3 3v1.5a1.5 1.5 0 01-3 0V6z"
                                clip-rule="evenodd" />
                            <path d="M6 12a2 2 0 012-2h8a2 2 0 012 2v2a2 2 0 01-2 2H2h2a2 2 0 002-2v-2z" />
                        </svg>
                        {{ __('Projects') }}
                    </div>
                    <svg class="h-5 w-5 transform transition-transform duration-200" :class="{ 'rotate-90': expanded }"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <div x-show="expanded" class="mt-2 space-y-1" style="display: none;">
                    <x-responsive-nav-link :href="route('projects.index')">{{ __('All Projects') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('projects.create')">{{ __('New Project') }}</x-responsive-nav-link>
                </div>
            </div>

            <!-- Features -->
            <div x-data="{ expanded: false }" class="border-l-4 border-transparent pl-3">
                <button @click="expanded = !expanded"
                    class="flex items-center justify-between w-full py-2 px-3 text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path
                                d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        {{ __('Features') }}
                    </div>
                    <svg class="h-5 w-5 transform transition-transform duration-200" :class="{ 'rotate-90': expanded }"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <div x-show="expanded" class="mt-2 space-y-1" style="display: none;">
                    <x-responsive-nav-link href="#">{{ __('Form Builder') }}</x-responsive-nav-link>
                    <x-responsive-nav-link href="#">{{ __('Products & Variants') }}</x-responsive-nav-link>
                    <x-responsive-nav-link href="#">{{ __('Invoices & Payments') }}</x-responsive-nav-link>
                    <x-responsive-nav-link href="#">{{ __('Financial Reports') }}</x-responsive-nav-link>
                </div>
            </div>

            <!-- Help & Support -->
            <div x-data="{ expanded: false }" class="border-l-4 border-transparent pl-3">
                <button @click="expanded = !expanded"
                    class="flex items-center justify-between w-full py-2 px-3 text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ __('Help & Support') }}
                    </div>
                    <svg class="h-5 w-5 transform transition-transform duration-200" :class="{ 'rotate-90': expanded }"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <div x-show="expanded" class="mt-2 space-y-1" style="display: none;">
                    <x-responsive-nav-link :href="route('user-guide')">{{ __('User Guide') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('faq')">{{ __('FAQ') }}</x-responsive-nav-link>
                </div>
            </div>

            <!-- Notifications -->
            <x-responsive-nav-link :href="route('notifications.index')" class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path
                            d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                    </svg>
                    {{ __('Notifications') }}
                </div>
                @if (auth()->user()->unreadNotificationsCount() > 0)
                    <span
                        class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
                        {{ auth()->user()->unreadNotificationsCount() }}
                    </span>
                @endif
            </x-responsive-nav-link>
        </div>

        <!-- Mobile Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4 flex items-center">
                <div class="flex-shrink-0">
                    <img class="h-10 w-10 rounded-full"
                        src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=7F9CF5&background=EBF4FF"
                        alt="{{ Auth::user()->name }}">
                </div>
                <div class="ml-3">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ __('Profile Settings') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();"
                        class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-500" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

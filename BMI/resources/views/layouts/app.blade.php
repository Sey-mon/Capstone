<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        <!-- Custom CSS -->
        <link rel="stylesheet" href="{{ asset('css/utilities.css') }}">
        <link rel="stylesheet" href="{{ asset('css/modern-dashboard.css') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --primary-50: #e8f5e9;
                --primary-100: #c8e6c9;
                --primary-500: #4caf50;
                --primary-600: #43a047;
                --primary-700: #388e3c;
                --primary-900: #1b5e20;
                --accent-blue: #185a9d;
                --accent-teal: #43cea2;
                --gray-50: #f8fafc;
                --gray-100: #f1f5f9;
                --gray-900: #0f172a;
            }
            
            body {
                font-family: 'Inter', sans-serif;
                font-feature-settings: 'cv02', 'cv03', 'cv04', 'cv11';
            }

            .sidebar-gradient {
                background: linear-gradient(180deg, #ffffff 0%, #e8f5e9 100%);
            }

            .nav-item {
                position: relative;
                transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .nav-item:hover {
                background: var(--primary-50);
                border-radius: 12px;
            }

            .nav-item.active {
                background: var(--primary-100);
                border-radius: 12px;
                color: var(--primary-700);
            }

            .nav-item.active::before {
                content: '';
                position: absolute;
                left: 0;
                top: 50%;
                transform: translateY(-50%);
                width: 4px;
                height: 24px;
                background: var(--primary-600);
                border-radius: 0 2px 2px 0;
            }

            .glass-effect {
                backdrop-filter: blur(20px);
                background: rgba(255, 255, 255, 0.95);
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-50" data-user-role="{{ auth()->user()->role ?? 'guest' }}">
        <div class="flex min-h-screen">
            <!-- Modern Sidebar -->
            <aside class="w-72 sidebar-gradient border-r border-gray-200/60 flex-shrink-0 fixed h-full top-0 left-0 z-30 flex flex-col shadow-sm">
                <!-- Logo Section -->
                <div class="flex items-center justify-center h-20 border-b border-gray-200/60">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center group-hover:scale-105 transition-transform duration-200">
                            <i class="fas fa-heartbeat text-white text-lg"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-lg font-semibold text-gray-900">Nutri Care</span>
                            <span class="text-xs text-gray-500 uppercase tracking-wide">{{ auth()->user()->role ?? 'System' }}</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 p-6 overflow-y-auto">
                    <div class="space-y-2">
                        @if(Auth::user()->isAdmin())
                            <div class="mb-6">
                                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Administration</h3>
                                <div class="space-y-1">
                                    <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:text-gray-900">
                                        <i class="fas fa-chart-line w-5 h-5 mr-3"></i>
                                        Dashboard
                                    </a>
                                    <a href="{{ route('admin.patients') }}" class="nav-item {{ request()->routeIs('admin.patients*') ? 'active' : '' }} flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:text-gray-900">
                                        <i class="fas fa-users w-5 h-5 mr-3"></i>
                                        Patients
                                    </a>
                                    <a href="{{ route('admin.nutrition') }}" class="nav-item {{ request()->routeIs('admin.nutrition*') ? 'active' : '' }} flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:text-gray-900">
                                        <i class="fas fa-apple-alt w-5 h-5 mr-3"></i>
                                        Nutrition
                                    </a>
                                    <a href="{{ route('admin.inventory') }}" class="nav-item {{ request()->routeIs('admin.inventory*') ? 'active' : '' }} flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:text-gray-900">
                                        <i class="fas fa-boxes w-5 h-5 mr-3"></i>
                                        Inventory
                                    </a>
                                    <a href="{{ route('admin.transactions') }}" class="nav-item {{ request()->routeIs('admin.transactions*') ? 'active' : '' }} flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:text-gray-900">
                                        <i class="fas fa-exchange-alt w-5 h-5 mr-3"></i>
                                        Transactions
                                    </a>
                                    <a href="{{ route('admin.reports') }}" class="nav-item {{ request()->routeIs('admin.reports') ? 'active' : '' }} flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:text-gray-900">
                                        <i class="fas fa-chart-bar w-5 h-5 mr-3"></i>
                                        Reports
                                    </a>
                                </div>
                            </div>
                            <div class="mb-6">
                                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">System</h3>
                                <div class="space-y-1">
                                    <a href="{{ route('admin.api-test') }}" class="nav-item {{ request()->routeIs('admin.api-test') ? 'active' : '' }} flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:text-gray-900">
                                        <i class="fas fa-code w-5 h-5 mr-3"></i>
                                        API Test
                                    </a>
                                    <a href="{{ route('admin.users') }}" class="nav-item {{ request()->routeIs('admin.users') ? 'active' : '' }} flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:text-gray-900">
                                        <i class="fas fa-user-cog w-5 h-5 mr-3"></i>
                                        Users
                                    </a>
                                    <a href="{{ route('admin.email-templates.index') }}" class="nav-item {{ request()->routeIs('admin.email-templates*') ? 'active' : '' }} flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:text-gray-900">
                                        <i class="fas fa-envelope w-5 h-5 mr-3"></i>
                                        Email Templates
                                    </a>
                                </div>
                            </div>
                        @elseif(Auth::user()->isNutritionist())
                            <div class="mb-6">
                                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Overview</h3>
                                <div class="space-y-1">
                                    <a href="{{ route('nutritionist.dashboard') }}" class="nav-item {{ request()->routeIs('nutritionist.dashboard') ? 'active' : '' }} flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:text-gray-900">
                                        <i class="fas fa-chart-line w-5 h-5 mr-3"></i>
                                        Dashboard
                                    </a>
                                    <a href="{{ route('nutritionist.treatment-model') }}" class="nav-item {{ request()->routeIs('nutritionist.treatment-model') ? 'active' : '' }} flex items-center px-4 py-3 text-sm font-medium text-green-600 hover:text-green-700">
                                        <i class="fas fa-robot w-5 h-5 mr-3"></i>
                                        AI Treatment Model
                                    </a>
                                </div>
                            </div>
                            <div class="mb-6">
                                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Patient Care</h3>
                                <div class="space-y-1">
                                    <a href="{{ route('nutritionist.patients') }}" class="nav-item {{ request()->routeIs('nutritionist.patients*') ? 'active' : '' }} flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:text-gray-900">
                                        <i class="fas fa-users w-5 h-5 mr-3"></i>
                                        Patients
                                    </a>
                                    <a href="{{ route('nutritionist.nutrition') }}" class="nav-item {{ request()->routeIs('nutritionist.nutrition*') ? 'active' : '' }} flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:text-gray-900">
                                        <i class="fas fa-apple-alt w-5 h-5 mr-3"></i>
                                        Assessments
                                    </a>
                                    <a href="{{ route('nutritionist.reports') }}" class="nav-item {{ request()->routeIs('nutritionist.reports') ? 'active' : '' }} flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:text-gray-900">
                                        <i class="fas fa-chart-bar w-5 h-5 mr-3"></i>
                                        Reports
                                    </a>
                                </div>
                            </div>
                            <div class="mb-6">
                                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Resources</h3>
                                <div class="space-y-1">
                                    <a href="{{ route('nutritionist.inventory') }}" class="nav-item {{ request()->routeIs('nutritionist.inventory*') ? 'active' : '' }} flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:text-gray-900">
                                        <i class="fas fa-boxes w-5 h-5 mr-3"></i>
                                        Inventory
                                    </a>
                                    <a href="{{ route('nutritionist.transactions') }}" class="nav-item {{ request()->routeIs('nutritionist.transactions*') ? 'active' : '' }} flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:text-gray-900">
                                        <i class="fas fa-exchange-alt w-5 h-5 mr-3"></i>
                                        Transactions
                                    </a>
                                </div>
                            </div>
                        @elseif(Auth::user()->isParent())
                            <a href="{{ route('profile.my-children') }}" class="nav-item {{ request()->routeIs('profile.my-children') ? 'active' : '' }} flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:text-gray-900">
                                <i class="fas fa-child w-5 h-5 mr-3"></i>
                                My Children
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }} flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:text-gray-900">
                                <i class="fas fa-chart-line w-5 h-5 mr-3"></i>
                                Dashboard
                            </a>
                        @endif
                    </div>
                </nav>

                <!-- User Profile Section -->
                <div class="p-6 border-t border-gray-200/60">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-gray-400 to-gray-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                </div>
            </aside>
            
            <!-- Main Content Area -->
            <div class="flex-1 ml-72">
                <!-- Modern Header -->
                <header class="glass-effect border-b border-gray-200/60 sticky top-0 z-20">
                    <div class="max-w-7xl mx-auto px-6 lg:px-8">
                        <div class="flex justify-between h-16 items-center">
                            <div class="flex items-center space-x-4">
                                @isset($header)
                                    <div class="text-xl font-semibold text-gray-900">
                                        {{ $header }}
                                    </div>
                                @endisset
                            </div>
                            
                            <!-- Header Actions -->
                            <div class="flex items-center space-x-4">
                                <!-- Notifications -->
                                <button class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-bell text-lg"></i>
                                </button>
                                
                                <!-- Settings Dropdown -->
                                <div class="relative">
                                    <x-dropdown align="right" width="56">
                                        <x-slot name="trigger">
                                            <button class="flex items-center space-x-3 px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200">
                                                <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-user text-white text-xs"></i>
                                                </div>
                                                <div class="text-left">
                                                    <div class="font-medium">{{ Auth::user()->name }}</div>
                                                    <div class="text-xs text-gray-500">{{ ucfirst(Auth::user()->role) }}</div>
                                                </div>
                                                <i class="fas fa-chevron-down text-xs"></i>
                                            </button>
                                        </x-slot>
                                        <x-slot name="content">
                                            <div class="py-2">
                                                <x-dropdown-link :href="route('profile.edit')" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    <i class="fas fa-user-edit w-4 h-4 mr-3"></i>
                                                    {{ __('Profile') }}
                                                </x-dropdown-link>
                                                <div class="border-t border-gray-100 my-1"></div>
                                                <form method="POST" action="{{ route('logout') }}">
                                                    @csrf
                                                    <x-dropdown-link :href="route('logout')"
                                                            onclick="event.preventDefault(); this.closest('form').submit();"
                                                            class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                        <i class="fas fa-sign-out-alt w-4 h-4 mr-3"></i>
                                                        {{ __('Log Out') }}
                                                    </x-dropdown-link>
                                                </form>
                                            </div>
                                        </x-slot>
                                    </x-dropdown>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Main Content -->
                <main class="flex-1">
                    @if (isset($slot))
                        {{ $slot }}
                    @else
                        @yield('content')
                    @endif
                </main>
            </div>
        </div>
    </body>
</html>

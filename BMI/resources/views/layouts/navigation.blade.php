<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-200 flex-shrink-0 fixed h-full top-0 left-0 z-30 flex flex-col">
        <div class="flex items-center justify-center h-16 border-b border-gray-200">
            <a href="{{ route('dashboard') }}">
                <x-application-logo class="block h-9 w-auto fill-current text-green-600" />
            </a>
        </div>
        <nav class="flex-1 p-4 overflow-y-auto">
            <ul class="space-y-2">
                @if(Auth::user()->isAdmin())
                    <li><x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">Dashboard</x-nav-link></li>
                    <li><x-nav-link :href="route('admin.patients')" :active="request()->routeIs('admin.patients*')">Patients</x-nav-link></li>
                    <li><x-nav-link :href="route('admin.nutrition')" :active="request()->routeIs('admin.nutrition*')">Nutrition</x-nav-link></li>
                    <li><x-nav-link :href="route('admin.inventory')" :active="request()->routeIs('admin.inventory*')">Inventory</x-nav-link></li>
                    <li><x-nav-link :href="route('admin.transactions')" :active="request()->routeIs('admin.transactions*')">Transactions</x-nav-link></li>
                    <li><x-nav-link :href="route('admin.reports')" :active="request()->routeIs('admin.reports')">Reports</x-nav-link></li>
                    <li><x-nav-link :href="route('admin.api-test')" :active="request()->routeIs('admin.api-test')">API Test</x-nav-link></li>
                    <li><x-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users')">Users</x-nav-link></li>
                    <li><x-nav-link :href="route('admin.email-templates.index')" :active="request()->routeIs('admin.email-templates*')">Email Templates</x-nav-link></li>
                @elseif(Auth::user()->isNutritionist())
                    <li><x-nav-link :href="route('nutritionist.dashboard')" :active="request()->routeIs('nutritionist.dashboard')">Dashboard</x-nav-link></li>
                    <li><x-nav-link :href="route('nutritionist.patients')" :active="request()->routeIs('nutritionist.patients*')">Patients</x-nav-link></li>
                    <li><x-nav-link :href="route('nutritionist.nutrition')" :active="request()->routeIs('nutritionist.nutrition*')">Nutrition</x-nav-link></li>
                    <li><x-nav-link :href="route('nutritionist.inventory')" :active="request()->routeIs('nutritionist.inventory*')">Inventory</x-nav-link></li>
                    <li><x-nav-link :href="route('nutritionist.transactions')" :active="request()->routeIs('nutritionist.transactions*')">Transactions</x-nav-link></li>
                    <li><x-nav-link :href="route('nutritionist.reports')" :active="request()->routeIs('nutritionist.reports')">Reports</x-nav-link></li>
                @elseif(Auth::user()->isParent())
                    <li><x-nav-link :href="route('profile.my-children')" :active="request()->routeIs('profile.my-children')">My Children</x-nav-link></li>
                @else
                    <li><x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-nav-link></li>
                @endif
            </ul>
        </nav>
    </aside>
    <!-- Main Content Area with Header -->
    <div class="flex-1 ml-64">
        <nav class="bg-green-600 border-b border-green-700 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center">
                        <!-- Branding (optional, can be removed if redundant with sidebar) -->
                    </div>
                    <!-- Settings Dropdown -->
                    <div class="flex items-center">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-green-400 text-sm leading-4 font-medium rounded-md text-white bg-green-700 hover:bg-green-800 focus:outline-none transition ease-in-out duration-150">
                                    <div>{{ Auth::user()->name }}</div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Profile') }}
                                </x-dropdown-link>
                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </div>
        </nav>
        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>
</div>

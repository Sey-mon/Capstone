<nav x-data="{ open: false }" class="bg-green-600 border-b border-green-700 text-white">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-white" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @if(Auth::user()->isAdmin())
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="text-white hover:text-green-100 hover:border-green-300" activeClass="border-white text-white">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.patients')" :active="request()->routeIs('admin.patients*')" class="text-white hover:text-green-100 hover:border-green-300" activeClass="border-white text-white">
                            {{ __('Patients') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.nutrition')" :active="request()->routeIs('admin.nutrition*')" class="text-white hover:text-green-100 hover:border-green-300" activeClass="border-white text-white">
                            {{ __('Nutrition') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.inventory')" :active="request()->routeIs('admin.inventory*')" class="text-white hover:text-green-100 hover:border-green-300" activeClass="border-white text-white">
                            {{ __('Inventory') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.transactions')" :active="request()->routeIs('admin.transactions*')" class="text-white hover:text-green-100 hover:border-green-300" activeClass="border-white text-white">
                            {{ __('Transactions') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.reports')" :active="request()->routeIs('admin.reports')" class="text-white hover:text-green-100 hover:border-green-300" activeClass="border-white text-white">
                            {{ __('Reports') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.api-test')" :active="request()->routeIs('admin.api-test')" class="text-white hover:text-green-100 hover:border-green-300" activeClass="border-white text-white">
                            {{ __('API Test') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users')" class="text-white hover:text-green-100 hover:border-green-300" activeClass="border-white text-white">
                            {{ __('Users') }}
                        </x-nav-link>
                    @elseif(Auth::user()->isNutritionist())
                        <x-nav-link :href="route('nutritionist.dashboard')" :active="request()->routeIs('nutritionist.dashboard')" class="text-white hover:text-green-100 hover:border-green-300" activeClass="border-white text-white">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('nutritionist.patients')" :active="request()->routeIs('nutritionist.patients*')" class="text-white hover:text-green-100 hover:border-green-300" activeClass="border-white text-white">
                            {{ __('Patients') }}
                        </x-nav-link>
                        <x-nav-link :href="route('nutritionist.nutrition')" :active="request()->routeIs('nutritionist.nutrition*')" class="text-white hover:text-green-100 hover:border-green-300" activeClass="border-white text-white">
                            {{ __('Nutrition') }}
                        </x-nav-link>
                        <x-nav-link :href="route('nutritionist.inventory')" :active="request()->routeIs('nutritionist.inventory*')" class="text-white hover:text-green-100 hover:border-green-300" activeClass="border-white text-white">
                            {{ __('Inventory') }}
                        </x-nav-link>
                        <x-nav-link :href="route('nutritionist.transactions')" :active="request()->routeIs('nutritionist.transactions*')" class="text-white hover:text-green-100 hover:border-green-300" activeClass="border-white text-white">
                            {{ __('Transactions') }}
                        </x-nav-link>
                        <x-nav-link :href="route('nutritionist.reports')" :active="request()->routeIs('nutritionist.reports')" class="text-white hover:text-green-100 hover:border-green-300" activeClass="border-white text-white">
                            {{ __('Reports') }}
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white hover:text-green-100 hover:border-green-300" activeClass="border-white text-white">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
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

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-green-100 hover:bg-green-700 focus:outline-none focus:bg-green-700 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-green-700">
        <div class="pt-2 pb-3 space-y-1">
            @if(Auth::user()->isAdmin())
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="text-white hover:bg-green-800 hover:text-white">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.patients')" :active="request()->routeIs('admin.patients*')" class="text-white hover:bg-green-800 hover:text-white">
                    {{ __('Patients') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.nutrition')" :active="request()->routeIs('admin.nutrition*')" class="text-white hover:bg-green-800 hover:text-white">
                    {{ __('Nutrition') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.inventory')" :active="request()->routeIs('admin.inventory*')" class="text-white hover:bg-green-800 hover:text-white">
                    {{ __('Inventory') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.transactions')" :active="request()->routeIs('admin.transactions*')" class="text-white hover:bg-green-800 hover:text-white">
                    {{ __('Transactions') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.reports')" :active="request()->routeIs('admin.reports')" class="text-white hover:bg-green-800 hover:text-white">
                    {{ __('Reports') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.api-test')" :active="request()->routeIs('admin.api-test')" class="text-white hover:bg-green-800 hover:text-white">
                    {{ __('API Test') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users')" class="text-white hover:bg-green-800 hover:text-white">
                    {{ __('Users') }}
                </x-responsive-nav-link>
            @elseif(Auth::user()->isNutritionist())
                <x-responsive-nav-link :href="route('nutritionist.dashboard')" :active="request()->routeIs('nutritionist.dashboard')" class="text-white hover:bg-green-800 hover:text-white">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('nutritionist.patients')" :active="request()->routeIs('nutritionist.patients*')" class="text-white hover:bg-green-800 hover:text-white">
                    {{ __('Patients') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('nutritionist.nutrition')" :active="request()->routeIs('nutritionist.nutrition*')" class="text-white hover:bg-green-800 hover:text-white">
                    {{ __('Nutrition') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('nutritionist.inventory')" :active="request()->routeIs('nutritionist.inventory*')" class="text-white hover:bg-green-800 hover:text-white">
                    {{ __('Inventory') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('nutritionist.transactions')" :active="request()->routeIs('nutritionist.transactions*')" class="text-white hover:bg-green-800 hover:text-white">
                    {{ __('Transactions') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('nutritionist.reports')" :active="request()->routeIs('nutritionist.reports')" class="text-white hover:bg-green-800 hover:text-white">
                    {{ __('Reports') }}
                </x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white hover:bg-green-800 hover:text-white">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-green-600">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-green-200">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-white hover:bg-green-800 hover:text-white">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            class="text-white hover:bg-green-800 hover:text-white"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

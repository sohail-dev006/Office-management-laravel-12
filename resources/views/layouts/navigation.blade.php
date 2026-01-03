<nav x-data="{ open: false }" class=" dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img style="width: 50px;" src="data:image/png;base64,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" alt="">
                    </a>
                </div>

                <!-- Navigation Links -->
                <!-- <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div> -->
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 text-white">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot  name="content" >
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
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                Authentication
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link class="" :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

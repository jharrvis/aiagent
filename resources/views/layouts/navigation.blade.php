<nav x-data="{ 
    open: false,
    darkMode: localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
    toggleTheme() {
        this.darkMode = !this.darkMode;
        if (this.darkMode) {
            document.documentElement.classList.add('dark');
            localStorage.setItem('color-theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('color-theme', 'light');
        }
    }
}" class="bg-white dark:bg-slate-900 border-b border-gray-100 dark:border-slate-800">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-slate-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('marketplace')" :active="request()->routeIs('marketplace')"
                        class="dark:text-slate-300 dark:hover:text-white">
                        {{ __('Agen AI') }}
                    </x-nav-link>
                    @auth
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                            class="dark:text-slate-300 dark:hover:text-white">
                            {{ __('Dasbor') }}
                        </x-nav-link>
                        <x-nav-link :href="route('gallery')" :active="request()->routeIs('gallery')"
                            class="dark:text-slate-300 dark:hover:text-white">
                            {{ __('Galeri Gambar') }}
                        </x-nav-link>
                        @if(auth()->user()->is_admin)
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')"
                                class="dark:text-slate-300 dark:hover:text-white">
                                {{ __('Admin') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings / Theme Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-2">
                <!-- Theme Switcher -->
                <button @click="toggleTheme()"
                    class="p-2 rounded-md text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                    <span x-show="!darkMode" class="material-symbols-outlined text-[20px]">dark_mode</span>
                    <span x-show="darkMode" class="material-symbols-outlined text-[20px]">light_mode</span>
                </button>
                @auth
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-slate-900 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                    <div>{{ Auth::user()->name }}</div>

                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')"
                                    class="dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">
                                    {{ __('Profil') }}
                                </x-dropdown-link>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                                this.closest('form').submit();"
                                        class="dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">
                                        {{ __('Keluar') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @else
                    <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-slate-400 underline">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="text-sm text-gray-700 dark:text-slate-400 underline">Daftar</a>
                        @endif
                    </div>
                @endauth

                <!-- Hamburger -->
                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('marketplace')" :active="request()->routeIs('marketplace')"
                    class="dark:text-slate-300">
                    {{ __('Agen AI') }}
                </x-responsive-nav-link>
                @auth
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                        class="dark:text-slate-300">
                        {{ __('Dasbor') }}
                    </x-responsive-nav-link>
                    @if(auth()->user()->is_admin)
                        <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')"
                            class="dark:text-slate-300">
                            {{ __('Admin') }}
                        </x-responsive-nav-link>
                    @endif
                @endauth
                <!-- Mobile Theme Toggle -->
                <button @click="toggleTheme()"
                    class="flex w-full items-center px-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-gray-600 dark:text-slate-300 hover:text-gray-800 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-slate-800 hover:border-gray-300 transition duration-150 ease-in-out">
                    <div class="flex items-center gap-2">
                        <span x-show="!darkMode" class="material-symbols-outlined text-[20px]">dark_mode</span>
                        <span x-show="darkMode" class="material-symbols-outlined text-[20px]">light_mode</span>
                        <span x-text="darkMode ? 'Mode Terang' : 'Mode Gelap'"></span>
                    </div>
                </button>
            </div>

            <!-- Responsive Settings Options -->
            @auth
                <div class="pt-4 pb-1 border-t border-gray-200">
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-800 dark:text-slate-200">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500 dark:text-slate-400">{{ Auth::user()->email }}</div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <x-responsive-nav-link :href="route('profile.edit')" class="dark:text-slate-300">
                            {{ __('Profil') }}
                        </x-responsive-nav-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault();
                                                    this.closest('form').submit();" class="dark:text-slate-300">
                                {{ __('Keluar') }}
                            </x-responsive-nav-link>
                        </form>
                    </div>
                </div>
            @else
                <div class="pt-4 pb-1 border-t border-gray-200 dark:border-slate-800">
                    <div class="mt-3 space-y-1">
                        <x-responsive-nav-link :href="route('login')" class="dark:text-slate-300">
                            {{ __('Masuk') }}
                        </x-responsive-nav-link>
                        @if (Route::has('register'))
                            <x-responsive-nav-link :href="route('register')" class="dark:text-slate-300">
                                {{ __('Daftar') }}
                            </x-responsive-nav-link>
                        @endif
                    </div>
                </div>
            @endauth
        </div>
</nav>
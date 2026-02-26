{{-- Navigation with off-canvas mobile panel --}}
<nav x-data="{
        mobileOpen: false,
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
    }" class="relative bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 z-40">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            {{-- Logo / Brand --}}
            <div class="flex items-center gap-3 shrink-0">
                <div class="h-8 w-8 bg-blue-600 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-[18px]">smart_toy</span>
                </div>
                <span class="font-bold text-slate-900 dark:text-white text-lg tracking-tight hidden sm:block">AI
                    Agent</span>
            </div>

            {{-- Desktop Nav Links --}}
            <div class="hidden sm:flex items-center gap-1">
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('dashboard') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                        Dasbor
                    </a>
                    <a href="{{ route('marketplace') }}"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('marketplace') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                        Asisten CEO
                    </a>
                    <a href="{{ route('conversations.index') }}"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('conversations.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                        Percakapan
                    </a>
                    <a href="{{ route('gallery') }}"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('gallery') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                        Galeri Gambar
                    </a>
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                            Admin
                        </a>
                    @endif
                @endauth
            </div>

            {{-- Desktop Right Actions --}}
            <div class="hidden sm:flex items-center gap-2">
                {{-- Theme Toggle --}}
                <button @click="toggleTheme()"
                    class="p-2 rounded-lg text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-700 dark:hover:text-slate-200 transition-all">
                    <span x-show="!darkMode" class="material-symbols-outlined text-[20px]">dark_mode</span>
                    <span x-show="darkMode" class="material-symbols-outlined text-[20px]">light_mode</span>
                </button>

                @auth
                    {{-- Token Balance Badge --}}
                    <div
                        class="hidden md:flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 dark:bg-slate-800 rounded-lg mr-2 border border-slate-200 dark:border-slate-700">
                        <span class="material-symbols-outlined text-[16px] text-amber-500">diamond</span>
                        <span id="navbar-token-desktop"
                            class="text-sm font-bold {{ auth()->user()->token_balance < 500 && !auth()->user()->is_admin ? 'text-red-500' : 'text-slate-700 dark:text-slate-300' }}">
                            @if(auth()->user()->is_admin)
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[16px]">all_inclusive</span>
                                    {{ __('Unlimited') }}
                                </span>
                            @else
                                {{ number_format(auth()->user()->token_balance, 0, ',', '.') }}
                            @endif
                        </span>
                    </div>

                    {{-- User Dropdown --}}
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                                <div
                                    class="h-7 w-7 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs font-bold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span>{{ Auth::user()->name }}</span>
                                <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')"
                                class="dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">
                                {{ __('Profil') }}
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">
                                    {{ __('Keluar') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}"
                        class="px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">
                        Masuk
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-all shadow-sm">
                            Daftar
                        </a>
                    @endif
                @endauth
            </div>

            {{-- Mobile: Theme + Hamburger --}}
            <div class="flex sm:hidden items-center gap-2">
                <button @click="toggleTheme()"
                    class="p-2 rounded-lg text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                    <span x-show="!darkMode" class="material-symbols-outlined text-[20px]">dark_mode</span>
                    <span x-show="darkMode" class="material-symbols-outlined text-[20px]">light_mode</span>
                </button>
                <button @click="mobileOpen = true"
                    class="p-2 rounded-lg text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                    <span class="material-symbols-outlined text-[24px]">menu</span>
                </button>
            </div>

        </div>
    </div>

    {{-- ========== OFF-CANVAS MOBILE PANEL ========== --}}

    {{-- Backdrop --}}
    <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="mobileOpen = false"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 md:hidden" x-cloak>
    </div>

    {{-- Side Panel --}}
    <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="fixed top-0 right-0 h-full w-72 bg-white dark:bg-slate-900 shadow-2xl z-50 flex flex-col md:hidden transform"
        x-cloak>

        {{-- Panel Header --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200 dark:border-slate-800">
            <div class="flex items-center gap-3">
                <div class="h-8 w-8 bg-blue-600 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-[18px]">smart_toy</span>
                </div>
                <span class="font-bold text-slate-900 dark:text-white text-base">AI Agent</span>
            </div>
            <button @click="mobileOpen = false"
                class="p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-600 transition-all">
                <span class="material-symbols-outlined text-[22px]">close</span>
            </button>
        </div>

        {{-- Navigation Links --}}
        <div class="flex-1 overflow-y-auto px-4 py-4 space-y-1">
            @auth
                {{-- Mobile Token Balance --}}
                <div
                    class="flex items-center justify-between px-3 py-3 mb-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-200 dark:border-slate-800">
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Sisa
                        Token</span>
                    <div class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px] text-amber-500">diamond</span>
                        <span id="navbar-token-mobile"
                            class="text-sm font-bold {{ auth()->user()->token_balance < 500 && !auth()->user()->is_admin ? 'text-red-500' : 'text-slate-700 dark:text-slate-200' }}">
                            @if(auth()->user()->is_admin)
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[16px]">all_inclusive</span>
                                    {{ __('Unlimited') }}
                                </span>
                            @else
                                {{ number_format(auth()->user()->token_balance, 0, ',', '.') }}
                            @endif
                        </span>
                    </div>
                </div>

                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest px-3 mb-2">Menu
                </p>

                <a href="{{ route('dashboard') }}" @click="mobileOpen = false"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('dashboard') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <span class="material-symbols-outlined text-[20px]">dashboard</span>
                    Dasbor
                </a>
                <a href="{{ route('marketplace') }}" @click="mobileOpen = false"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('marketplace') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <span class="material-symbols-outlined text-[20px]">explore</span>
                    Asisten CEO
                </a>
                <a href="{{ route('conversations.index') }}" @click="mobileOpen = false"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('conversations.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <span class="material-symbols-outlined text-[20px]">chat</span>
                    Percakapan
                </a>
                <a href="{{ route('gallery') }}" @click="mobileOpen = false"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('gallery') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <span class="material-symbols-outlined text-[20px]">photo_library</span>
                    Galeri Gambar
                </a>
                @if(auth()->user()->is_admin)
                    <a href="{{ route('admin.dashboard') }}" @click="mobileOpen = false"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                        <span class="material-symbols-outlined text-[20px]">admin_panel_settings</span>
                        Admin
                    </a>
                @endif

                <div class="h-px bg-slate-200 dark:bg-slate-800 my-3"></div>

                {{-- Theme Toggle (mobile panel) --}}
                <button @click="toggleTheme()"
                    class="flex items-center gap-3 w-full px-3 py-2.5 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                    <span x-show="!darkMode" class="material-symbols-outlined text-[20px]">dark_mode</span>
                    <span x-show="darkMode" class="material-symbols-outlined text-[20px]">light_mode</span>
                    <span x-text="darkMode ? 'Mode Terang' : 'Mode Gelap'"></span>
                </button>

            @else
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest px-3 mb-2">Menu
                </p>

                <a href="{{ route('marketplace') }}" @click="mobileOpen = false"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                    <span class="material-symbols-outlined text-[20px]">explore</span>
                    Asisten CEO
                </a>

                <div class="h-px bg-slate-200 dark:bg-slate-800 my-3"></div>

                <button @click="toggleTheme()"
                    class="flex items-center gap-3 w-full px-3 py-2.5 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                    <span x-show="!darkMode" class="material-symbols-outlined text-[20px]">dark_mode</span>
                    <span x-show="darkMode" class="material-symbols-outlined text-[20px]">light_mode</span>
                    <span x-text="darkMode ? 'Mode Terang' : 'Mode Gelap'"></span>
                </button>
            @endauth
        </div>

        {{-- Panel Footer: User Info --}}
        @auth
            <div class="border-t border-slate-200 dark:border-slate-800 px-5 py-4">
                <div class="flex items-center gap-3 mb-3">
                    <div
                        class="h-9 w-9 rounded-full bg-blue-600 flex items-center justify-center text-white text-sm font-bold shrink-0">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-slate-900 dark:text-white truncate">{{ Auth::user()->name }}
                        </p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-lg transition-all">
                        <span class="material-symbols-outlined text-[16px]">person</span>
                        Profil
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex items-center justify-center gap-1.5 w-full px-3 py-2 text-xs font-medium text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30 rounded-lg transition-all">
                            <span class="material-symbols-outlined text-[16px]">logout</span>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="border-t border-slate-200 dark:border-slate-800 px-5 py-4 space-y-2">
                <a href="{{ route('login') }}"
                    class="block w-full text-center px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition-all">
                    Masuk
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                        class="block w-full text-center px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-all shadow-sm">
                        Daftar
                    </a>
                @endif
            </div>
        @endauth

    </div>
    {{-- ========== END OFF-CANVAS PANEL ========== --}}

</nav>
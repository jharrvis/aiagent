<x-guest-layout>
    <div class="flex h-screen w-full">
        <!-- Left Side: Visual Showcase -->
        <div class="hidden lg:flex lg:w-1/2 relative flex-col justify-between p-8 xl:p-12 overflow-hidden bg-[#0a0f18]">
            <!-- Background Image/Effect -->
            <div class="absolute inset-0 z-0 opacity-60">
                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBcYu9O_NCvem0XJBGBzfKRnHR7vXg_Gy2MwvtrjmlejJUawOVDBinaD_WLlJ5S_0N9ll1hrxW4l0HDytgnn0lHEgzsC6vwPhBssHkvnBkqSH0nG0Qklhv70GKoAA7ESdpp3nQp4U6fHhbACLOzasWF1fD38Pwi5FprDgpp_5KyU4PrK01yoaob7DKGMa_-bJvd8GK37uaR9nYbjn5CD-02FR_boFgc7T4gOGe4hMi8tY-X6AGEJpE7pv1v-VzFZe-CMJ9LVPKkGJ0"
                    alt="AI Network" class="w-full h-full object-cover">
                <div
                    class="absolute inset-0 bg-gradient-to-t from-background-dark via-background-dark/50 to-transparent">
                </div>
            </div>

            <!-- Brand Logo -->
            <div class="relative z-10 flex items-center gap-3 text-white">
                <a href="{{ route('marketplace') }}" class="flex items-center gap-3 text-white hover:opacity-80 transition-opacity">
                    <div
                        class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/20 backdrop-blur-sm border border-primary/30 text-primary">
                        <span class="material-symbols-outlined text-2xl">smart_toy</span>
                    </div>
                    <span class="text-xl font-bold tracking-tight">Asisten CEO</span>
                </a>
            </div>

            <!-- Content Overlay -->
            <div class="relative z-10 max-w-lg">
                <h1 class="text-4xl xl:text-5xl font-extrabold tracking-tight text-white mb-6 leading-tight">
                    {{ __('Mulai perjalanan cerdas Anda dengan Asisten CEO.') }}
                </h1>
                <p class="text-lg text-slate-400 mb-8 leading-relaxed">
                    {{ __('Buat akun hari ini dan rasakan kemudahan mengelola tugas dengan asisten AI yang andal. Dari analis bisnis hingga penulis kreatif, Asisten CEO membantu Anda bekerja lebih cerdas.') }}
                </p>
            </div>
        </div>

        <!-- Right Side: Register Form -->
        <div
            class="flex-1 flex flex-col justify-center items-center p-6 md:p-12 lg:w-1/2 bg-background-light dark:bg-background-dark overflow-y-auto">
            <!-- Mobile Header Logo -->
            <div class="lg:hidden w-full max-w-md mb-8 flex items-center gap-2">
                <a href="{{ route('marketplace') }}" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-primary/10 text-primary">
                        <span class="material-symbols-outlined text-xl">smart_toy</span>
                    </div>
                    <span class="text-lg font-bold text-slate-900 dark:text-white">Asisten CEO</span>
                </a>
            </div>

            <div class="w-full max-w-md space-y-8">
                <div class="text-center lg:text-left">
                    <h2 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">{{ __('Buat Akun Asisten CEO') }}
                    </h2>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                        {{ __('Daftar untuk mulai menjelajahi Asisten CEO AI yang membantu meningkatkan produktivitas Anda.') }}
                    </p>
                </div>

                <div class="mt-10">
                    <!-- Social Login Buttons -->
                    <div class="grid grid-cols-1 gap-4">
                        <a href="{{ route('auth.google') }}"
                            class="flex w-full items-center justify-center gap-3 rounded-lg bg-white dark:bg-surface-dark px-3 py-2.5 text-sm font-semibold text-slate-900 dark:text-white shadow-sm ring-1 ring-inset ring-slate-300 dark:ring-border-dark hover:bg-slate-50 dark:hover:bg-[#1c2636] transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">
                            <svg aria-hidden="true" class="h-5 w-5" viewBox="0 0 24 24">
                                <path
                                    d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                                    fill="#4285F4"></path>
                                <path
                                    d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                                    fill="#34A853"></path>
                                <path
                                    d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                                    fill="#FBBC05"></path>
                                <path
                                    d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                                    fill="#EA4335"></path>
                            </svg>
                            <span class="text-sm">Google</span>
                        </a>
                    </div>

                    <div class="relative mt-8">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-slate-300 dark:border-border-dark"></div>
                        </div>
                        <div class="relative flex justify-center text-sm font-medium leading-6">
                            <span
                                class="bg-background-light dark:bg-background-dark px-4 text-slate-500">{{ __('Atau daftar dengan email') }}</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-6">
                        @csrf

                        <!-- Name -->
                        <div>
                            <label for="name"
                                class="block text-sm font-medium leading-6 text-slate-900 dark:text-white">{{ __('Nama Lengkap') }}</label>
                            <div class="mt-2 relative">
                                <input id="name" type="text" name="name" :value="old('name')" required autofocus
                                    autocomplete="name"
                                    class="block w-full rounded-lg border-0 bg-white dark:bg-surface-dark py-3 px-4 text-slate-900 dark:text-white shadow-sm ring-1 ring-inset ring-slate-300 dark:ring-border-dark placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6 transition-all"
                                    placeholder="{{ __('Nama Anda') }}">
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="material-symbols-outlined text-slate-400 text-[20px]">person</span>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email Address -->
                        <div>
                            <label for="email"
                                class="block text-sm font-medium leading-6 text-slate-900 dark:text-white">{{ __('Alamat Email') }}</label>
                            <div class="mt-2 relative">
                                <input id="email" type="email" name="email" :value="old('email')" required
                                    autocomplete="username"
                                    class="block w-full rounded-lg border-0 bg-white dark:bg-surface-dark py-3 px-4 text-slate-900 dark:text-white shadow-sm ring-1 ring-inset ring-slate-300 dark:ring-border-dark placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6 transition-all"
                                    placeholder="nama@perusahaan.com">
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="material-symbols-outlined text-slate-400 text-[20px]">mail</span>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password"
                                class="block text-sm font-medium leading-6 text-slate-900 dark:text-white">{{ __('Kata Sandi') }}</label>
                            <div class="mt-2 relative">
                                <input id="password" type="password" name="password" required
                                    autocomplete="new-password"
                                    class="block w-full rounded-lg border-0 bg-white dark:bg-surface-dark py-3 px-4 text-slate-900 dark:text-white shadow-sm ring-1 ring-inset ring-slate-300 dark:ring-border-dark placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6 transition-all"
                                    placeholder="••••••••">
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="material-symbols-outlined text-slate-400 text-[20px]">lock</span>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation"
                                class="block text-sm font-medium leading-6 text-slate-900 dark:text-white">{{ __('Konfirmasi Kata Sandi') }}</label>
                            <div class="mt-2 relative">
                                <input id="password_confirmation" type="password" name="password_confirmation" required
                                    autocomplete="new-password"
                                    class="block w-full rounded-lg border-0 bg-white dark:bg-surface-dark py-3 px-4 text-slate-900 dark:text-white shadow-sm ring-1 ring-inset ring-slate-300 dark:ring-border-dark placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6 transition-all"
                                    placeholder="••••••••">
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span
                                        class="material-symbols-outlined text-slate-400 text-[20px]">enhanced_encryption</span>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div>
                            <button type="submit"
                                class="flex w-full justify-center rounded-lg bg-primary px-3 py-3 text-sm font-bold leading-6 text-white shadow-sm hover:bg-primary-hover focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-all">
                                {{ __('Daftar') }}
                            </button>
                        </div>
                    </form>

                    <p class="mt-8 text-center text-sm text-slate-500">
                        {{ __('Sudah punya akun?') }}
                        <a href="{{ route('login') }}"
                            class="font-semibold leading-6 text-primary hover:text-primary-hover transition-colors">
                            {{ __('Masuk sekarang') }}
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
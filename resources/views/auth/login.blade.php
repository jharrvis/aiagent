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
                    {{ __('Asisten AI pribadi untuk meningkatkan produktivitas Anda.') }}
                </h1>
                <p class="text-lg text-slate-400 mb-8 leading-relaxed">
                    {{ __('Asisten CEO AI membantu Anda mengelola tugas, menganalisis data, dan meningkatkan alur kerja dengan kecerdasan buatan. Dari analis bisnis hingga penulis kreatif, semua dalam satu platform.') }}
                </p>

                <!-- Testimonial / Social Proof -->
                <div class="flex items-center gap-4 pt-8 border-t border-white/10">
                    <div class="flex -space-x-3">
                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBgCCP1cq0bdSdldQKhaAhcxM8oh4-jDuT6cYohfWtrcSfKf6xuMV4tR6sNO2tvv5W04OUV1f6jn2bpBO_tYU6PJUa6qBWUUWwF9ciAb5FWhzaZHsOaJ8nMfeTRAkndcSYsytD1bnhzTG_aveH44ywmfUp5Oq7vBy4D6c056MyAtr5SNsqSHbmZSBvUh5cAO3t4yW1oHNjiPM8fI4tSrpe5YApEJosSrkSl3mownIjOpNdDTzAaJLsK8-xPpgA8UlPvh9vFtIRTV8A"
                            alt="User" class="inline-block h-10 w-10 rounded-full ring-2 ring-background-dark">
                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAb7nzZ0lPqbR0VJqDKrxaGZ62XP2-gGeTZbaEE7ItP6aSrOpUPHiG2SDmE0JU7NFONe_qotIOc508dktzg_iYP9tloxuouQQh7PeTCJFJ-Jigpg_bRt2FyKVi_bousv-B-anoMC_4iS6Jv-AUUD3hrW8B_FuKiSBvBVx3RbPi4HZ4PdDj4S8pIwZf10ukT-KBKVvHoxPAz2fK5v9w7c4Em59bTZcIxP5fA7eioj-XtPKuAVlev8mFYQEuQtLpkIJSaCtKG1GX_lqk"
                            alt="User" class="inline-block h-10 w-10 rounded-full ring-2 ring-background-dark">
                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDoY7_SRDod7hDPdkp8hyjoZ93kowk0XOTScgwfcxtX83lH41PZuF1goBvtlarZysuNtBGLiVjJUqOxQwJ6aYPYNU7lLfz8l1-gRJo7ZBHXVkGssqrzzSvACBqNfW8Yzhci8fTCKrXh6DNFTZCF4THpnk5EeOooJUtC1DSvejSYfxbVVuTDDVT-_EG8MIfnnAAb-jt4wa-4Q_nlRBMRPDa9zBdVgdhWEO4EM6N9FeWrBueEEkne8CLGDnO-NgCelhfDoknvrKSTRKM"
                            alt="User" class="inline-block h-10 w-10 rounded-full ring-2 ring-background-dark">
                    </div>
                    <div class="flex flex-col">
                        <div class="flex text-yellow-400 text-sm">
                            <span class="material-symbols-outlined text-[16px] fill-current">star</span>
                            <span class="material-symbols-outlined text-[16px] fill-current">star</span>
                            <span class="material-symbols-outlined text-[16px] fill-current">star</span>
                            <span class="material-symbols-outlined text-[16px] fill-current">star</span>
                            <span class="material-symbols-outlined text-[16px] fill-current">star</span>
                        </div>
                        <span
                            class="text-sm font-medium text-slate-300">{{ __('Dipercaya oleh 10.000+ developer') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Login Form -->
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
                    <h2 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">
                        {{ __('Selamat datang kembali') }}
                    </h2>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                        {{ __('Masukkan kredensial Anda untuk mengakses agen Anda.') }}
                    </p>
                </div>

                <div class="mt-10">
                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

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
                                class="bg-background-light dark:bg-background-dark px-4 text-slate-500">{{ __('Atau masuk dengan email') }}</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
                        @csrf

                        <!-- Email Address -->
                        <div>
                            <label for="email"
                                class="block text-sm font-medium leading-6 text-slate-900 dark:text-white">{{ __('Alamat Email') }}</label>
                            <div class="mt-2 relative">
                                <input id="email" type="email" name="email" :value="old('email')" required autofocus
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
                                    autocomplete="current-password"
                                    class="block w-full rounded-lg border-0 bg-white dark:bg-surface-dark py-3 px-4 text-slate-900 dark:text-white shadow-sm ring-1 ring-inset ring-slate-300 dark:ring-border-dark placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6 transition-all"
                                    placeholder="{{ __('Masukkan kata sandi Anda') }}">
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="material-symbols-outlined text-slate-400 text-[20px]">lock</span>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember_me" type="checkbox" name="remember"
                                    class="h-4 w-4 rounded border-slate-300 dark:border-border-dark text-primary focus:ring-primary bg-transparent dark:bg-surface-dark">
                                <label for="remember_me"
                                    class="ml-2 block text-sm leading-6 text-slate-700 dark:text-slate-300">{{ __('Ingat saya') }}</label>
                            </div>

                            @if (Route::has('password.request'))
                                <div class="text-sm leading-6">
                                    <a href="{{ route('password.request') }}"
                                        class="font-semibold text-primary hover:text-primary-hover transition-colors">
                                        {{ __('Lupa kata sandi?') }}
                                    </a>
                                </div>
                            @endif
                        </div>

                        <div>
                            <button type="submit"
                                class="flex w-full justify-center rounded-lg bg-primary px-3 py-3 text-sm font-bold leading-6 text-white shadow-sm hover:bg-primary-hover focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-all">
                                {{ __('Masuk') }}
                            </button>
                        </div>
                    </form>

                    <p class="mt-8 text-center text-sm text-slate-500">
                        {{ __('Belum punya akun?') }}
                        <a href="{{ route('register') }}"
                            class="font-semibold leading-6 text-primary hover:text-primary-hover transition-colors">
                            {{ __('Daftar sekarang') }}
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
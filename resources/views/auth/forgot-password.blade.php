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
                <div
                    class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/20 backdrop-blur-sm border border-primary/30 text-primary">
                    <span class="material-symbols-outlined text-2xl">neurology</span>
                </div>
                <span class="text-xl font-bold tracking-tight">AI Nexus</span>
            </div>

            <!-- Content Overlay -->
            <div class="relative z-10 max-w-lg">
                <h1 class="text-4xl xl:text-5xl font-extrabold tracking-tight text-white mb-6 leading-tight">
                    {{ __('Keamanan Anda adalah prioritas kami.') }}
                </h1>
                <p class="text-lg text-slate-400 mb-8 leading-relaxed">
                    {{ __('Atur ulang kata sandi Anda untuk mendapatkan kembali akses ke asisten AI cerdas Anda.') }}
                </p>
            </div>
        </div>

        <!-- Right Side: Forgot Password Form -->
        <div
            class="flex-1 flex flex-col justify-center items-center p-6 md:p-12 lg:w-1/2 bg-background-light dark:bg-background-dark overflow-y-auto">
            <!-- Mobile Header Logo -->
            <div class="lg:hidden w-full max-w-md mb-8 flex items-center gap-2">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-primary/10 text-primary">
                    <span class="material-symbols-outlined text-xl">neurology</span>
                </div>
                <span class="text-lg font-bold text-slate-900 dark:text-white">AI Nexus</span>
            </div>

            <div class="w-full max-w-md space-y-8">
                <div class="text-center lg:text-left">
                    <h2 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">
                        {{ __('Lupa Kata Sandi?') }}</h2>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                        {{ __('Jangan khawatir. Masukkan alamat email Anda dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.') }}
                    </p>
                </div>

                <div class="mt-10">
                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                        @csrf

                        <!-- Email Address -->
                        <div>
                            <label for="email"
                                class="block text-sm font-medium leading-6 text-slate-900 dark:text-white">{{ __('Alamat Email') }}</label>
                            <div class="mt-2 relative">
                                <input id="email" type="email" name="email" :value="old('email')" required autofocus
                                    class="block w-full rounded-lg border-0 bg-white dark:bg-surface-dark py-3 px-4 text-slate-900 dark:text-white shadow-sm ring-1 ring-inset ring-slate-300 dark:ring-border-dark placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6 transition-all"
                                    placeholder="nama@perusahaan.com">
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="material-symbols-outlined text-slate-400 text-[20px]">mail</span>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <button type="submit"
                                class="flex w-full justify-center rounded-lg bg-primary px-3 py-3 text-sm font-bold leading-6 text-white shadow-sm hover:bg-primary-hover focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-all">
                                {{ __('Kirim Tautan Atur Ulang') }}
                            </button>
                        </div>
                    </form>

                    <div class="mt-8 text-center pt-6 border-t border-slate-200 dark:border-slate-800">
                        <a href="{{ route('login') }}"
                            class="flex items-center justify-center gap-2 text-sm font-semibold text-primary hover:text-primary-hover transition-colors">
                            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                            {{ __('Kembali ke Masuk') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
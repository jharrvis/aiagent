<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-white leading-tight">
            {{ __('Panel Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">
                    {{ __('Ringkasan Sistem') }}
                </h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1">
                    {{ __('Pantau dan kelola infrastruktur kecerdasan buatan Anda.') }}
                </p>
            </div>

            @if($isQuotaExceeded)
                <div
                    class="mb-8 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 dark:bg-red-900/30 dark:text-red-400 rounded-r-xl shadow-sm animate-pulse">
                    <div class="flex items-center">
                        <span class="material-symbols-outlined mr-2">warning</span>
                        <p class="font-bold">{{ __('Quota Pemakaian Pelanggan Telah Habis!') }}</p>
                    </div>
                    <p class="text-sm mt-1">
                        {{ __('AI Agent tidak akan merespon hingga Anda melakukan top-up atau menaikkan limit di OpenRouter.') }}
                    </p>
                </div>
            @endif

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div
                    class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl shadow-sm">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">{{ __('Total Agen') }}
                    </p>
                    <h4 class="text-2xl font-black text-slate-900 dark:text-white">{{ number_format($totalAgents) }}
                    </h4>
                </div>
                <div
                    class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl shadow-sm">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">
                        {{ __('Total Pengguna') }}
                    </p>
                    <h4 class="text-2xl font-black text-slate-900 dark:text-white">{{ number_format($totalUsers) }}</h4>
                </div>
                <div
                    class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl shadow-sm">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">{{ __('Total Token') }}
                    </p>
                    <h4 class="text-2xl font-black text-blue-600 dark:text-blue-400">{{ number_format($totalTokens) }}
                    </h4>
                </div>
                <div
                    class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl shadow-sm {{ $isQuotaExceeded ? 'ring-2 ring-red-500' : '' }}">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">
                        {{ __('Tagihan Pelanggan') }}</p>
                    <h4 class="text-2xl font-black text-emerald-600 dark:text-emerald-400">
                        Rp {{ number_format($apiUsageIdr, 0, ',', '.') }}
                        <span class="text-xs font-medium text-slate-400">/ Rp
                            {{ number_format($apiLimitIdr, 0, ',', '.') }}</span>
                    </h4>
                    <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-tighter">
                        {{ __('Berdasarkan Quota Beli') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Manage Agents -->
                <a href="{{ route('admin.agents.index') }}"
                    class="group relative bg-white dark:bg-slate-900 overflow-hidden shadow-sm hover:shadow-xl sm:rounded-2xl p-8 border border-slate-200 dark:border-slate-800 transition-all hover:-translate-y-1">
                    <div class="flex flex-col">
                        <div
                            class="size-14 rounded-2xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 mb-4 transition-colors group-hover:bg-blue-600 group-hover:text-white">
                            <span class="material-symbols-outlined text-[32px]">smart_toy</span>
                        </div>
                        <div>
                            <h3
                                class="text-lg font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                {{ __('Kelola Agen AI') }}
                            </h3>
                            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1 leading-relaxed">
                                {{ __('Buat, konfigurasi, dan pantau performa tenaga kerja AI Anda.') }}
                            </p>
                        </div>
                    </div>
                </a>

                <!-- Analytics -->
                <div
                    class="group relative bg-white dark:bg-slate-900 overflow-hidden shadow-sm sm:rounded-2xl p-8 border border-slate-200 dark:border-slate-800 opacity-75">
                    <div class="flex flex-col">
                        <div
                            class="size-14 rounded-2xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400 mb-4">
                            <span class="material-symbols-outlined text-[32px]">analytics</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Analitik & Laporan') }}
                            </h3>
                            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1 leading-relaxed">
                                {{ __('Lihat statistik penggunaan dan efisiensi agen AI secara real-time.') }}
                            </p>
                        </div>
                    </div>
                    <div class="absolute top-4 right-4">
                        <span
                            class="px-2 py-1 bg-slate-100 dark:bg-slate-800 text-[10px] font-bold text-slate-500 rounded uppercase tracking-wider">{{ __('Segera Hadir') }}</span>
                    </div>
                </div>

                <!-- Settings -->
                <div
                    class="group relative bg-white dark:bg-slate-900 overflow-hidden shadow-sm sm:rounded-2xl p-8 border border-slate-200 dark:border-slate-800 opacity-75">
                    <div class="flex flex-col">
                        <div
                            class="size-14 rounded-2xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center text-amber-600 dark:text-amber-400 mb-4">
                            <span class="material-symbols-outlined text-[32px]">settings</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Pengaturan Sistem') }}
                            </h3>
                            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1 leading-relaxed">
                                {{ __('Konfigurasi kunci API, model penyedia, dan preferensi global.') }}
                            </p>
                        </div>
                    </div>
                    <div class="absolute top-4 right-4">
                        <span
                            class="px-2 py-1 bg-slate-100 dark:bg-slate-800 text-[10px] font-bold text-slate-500 rounded uppercase tracking-wider">{{ __('Segera Hadir') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
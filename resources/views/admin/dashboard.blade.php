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
                        {{ __('AI Agent tidak akan merespon hingga Anda melakukan top-up atau menaikkan limit API.') }}
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
                        {{ __('Tagihan Pelanggan') }}
                    </p>
                    <h4 class="text-2xl font-black text-emerald-600 dark:text-emerald-400">
                        Rp {{ number_format($apiUsageIdr, 0, ',', '.') }}
                        <span class="text-xs font-medium text-slate-400">/ Rp
                            {{ number_format($apiLimitIdr, 0, ',', '.') }}</span>
                    </h4>
                    <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-tighter">
                        {{ __('Berdasarkan Quota Beli') }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
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

                <!-- Manage Users -->
                <a href="{{ route('admin.users.index') }}"
                    class="group relative bg-white dark:bg-slate-900 overflow-hidden shadow-sm hover:shadow-xl sm:rounded-2xl p-8 border border-slate-200 dark:border-slate-800 transition-all hover:-translate-y-1">
                    <div class="flex flex-col">
                        <div
                            class="size-14 rounded-2xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400 mb-4 transition-colors group-hover:bg-emerald-600 group-hover:text-white">
                            <span class="material-symbols-outlined text-[32px]">group</span>
                        </div>
                        <div>
                            <h3
                                class="text-lg font-bold text-slate-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                                {{ __('Kelola Pengguna') }}
                            </h3>
                            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1 leading-relaxed">
                                {{ __('Lihat, tambah, dan kelola semua pengguna yang terdaftar.') }}
                            </p>
                        </div>
                    </div>
                </a>

                <!-- Excel Templates -->
                <a href="{{ route('admin.excel-templates.index') }}"
                    class="group relative bg-white dark:bg-slate-900 overflow-hidden shadow-sm hover:shadow-xl sm:rounded-2xl p-8 border border-slate-200 dark:border-slate-800 transition-all hover:-translate-y-1">
                    <div class="flex flex-col">
                        <div
                            class="size-14 rounded-2xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center text-amber-600 dark:text-amber-400 mb-4 transition-colors group-hover:bg-amber-600 group-hover:text-white">
                            <span class="material-symbols-outlined text-[32px]">table_chart</span>
                        </div>
                        <div>
                            <h3
                                class="text-lg font-bold text-slate-900 dark:text-white group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">
                                {{ __('Excel Templates') }}
                            </h3>
                            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1 leading-relaxed">
                                {{ __('Upload dan kelola template Excel untuk generate laporan otomatis.') }}
                            </p>
                        </div>
                    </div>
                </a>

                <!-- Analytics -->
                <a href="{{ route('admin.analytics.index') }}"
                    class="group relative bg-white dark:bg-slate-900 overflow-hidden shadow-sm hover:shadow-xl sm:rounded-2xl p-8 border border-slate-200 dark:border-slate-800 transition-all hover:-translate-y-1">
                    <div class="flex flex-col">
                        <div
                            class="size-14 rounded-2xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center text-purple-600 dark:text-purple-400 mb-4 transition-colors group-hover:bg-purple-600 group-hover:text-white">
                            <span class="material-symbols-outlined text-[32px]">analytics</span>
                        </div>
                        <div>
                            <h3
                                class="text-lg font-bold text-slate-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                                {{ __('Analitik & Laporan') }}
                            </h3>
                            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1 leading-relaxed">
                                {{ __('Pantau penggunaan API dan aktivitas model AI secara real-time.') }}
                            </p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Global Settings -->
            <div
                class="mt-8 bg-white dark:bg-slate-900 overflow-hidden shadow-sm sm:rounded-2xl border border-slate-200 dark:border-slate-800">
                <div class="p-6 sm:p-8">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">
                        {{ __('Pengaturan Global Token') }}
                    </h3>

                    @if(session('success'))
                        <div
                            class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-900/30 dark:text-green-400">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.settings.save') }}" method="POST" class="max-w-xl">
                        @csrf
                        <div class="mb-4">
                            <label for="default_free_tokens"
                                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                {{ __('Kuota Token Gratis Pengguna Baru') }}
                            </label>
                            <div class="flex items-center gap-3">
                                <input type="number" name="default_free_tokens" id="default_free_tokens"
                                    value="{{ old('default_free_tokens', $defaultFreeTokens) }}"
                                    class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                                    min="0" step="1000" required>
                                <button type="submit"
                                    class="shrink-0 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                    {{ __('Simpan') }}
                                </button>
                            </div>
                            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                                {{ __('Setiap pengguna yang baru mendaftar akan otomatis mendapatkan saldo token ini secara gratis.') }}
                            </p>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
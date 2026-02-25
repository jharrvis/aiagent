<x-app-layout>
    <div class="py-6 lg:py-10">
        <div class="max-w-[1400px] mx-auto px-4 lg:px-8 flex flex-col gap-8">
            <!-- Hero Section -->
            <div
                class="relative overflow-hidden rounded-2xl bg-slate-900 dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-500/10 via-transparent to-purple-500/5"></div>
                <div
                    class="relative z-10 p-8 md:p-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                    <div class="max-w-xl">
                        <h1 class="text-3xl md:text-4xl font-bold text-white mb-3">{{ __('Temukan Agen AI Terbaik') }}
                        </h1>
                        <p class="text-slate-400 text-lg">
                            {{ __('Jelajahi pasar agen AI khusus kami untuk meningkatkan alur kerja Anda. Dari asisten coding hingga penulis kreatif.') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Filters Strip -->
            <div class="flex flex-wrap items-center gap-3 pb-2 overflow-x-auto no-scrollbar">
                <button
                    class="flex items-center gap-2 px-4 py-2 rounded-full bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-sm font-medium whitespace-nowrap">
                    {{ __('Paling Populer') }}
                </button>
                <button
                    class="flex items-center gap-2 px-4 py-2 rounded-full border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-sm font-medium transition-colors whitespace-nowrap">
                    {{ __('Terbaru') }}
                </button>
                <button
                    class="flex items-center gap-2 px-4 py-2 rounded-full border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-sm font-medium transition-colors whitespace-nowrap">
                    {{ __('Rating Tertinggi') }}
                </button>
                <button
                    class="flex items-center gap-2 px-4 py-2 rounded-full border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-sm font-medium transition-colors whitespace-nowrap">
                    {{ __('Gratis') }}
                </button>
                <div class="hidden sm:block w-px h-6 bg-slate-300 dark:bg-slate-800 mx-2"></div>
                <button
                    class="flex items-center gap-1 text-slate-500 dark:text-slate-400 hover:text-blue-600 text-sm font-medium whitespace-nowrap ml-auto">
                    <span class="material-symbols-outlined text-[18px]">tune</span>
                    {{ __('Filter Lanjutan') }}
                </button>
            </div>

            <!-- Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($agents ?? \App\Models\Agent::where('is_active', true)->get() as $agent)
                    <div
                        class="group flex flex-col bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden hover:border-blue-500/50 hover:shadow-xl hover:shadow-blue-500/5 transition-all duration-300">
                        <div class="p-5 flex flex-col gap-4 flex-1">
                            <div class="flex justify-between items-start">
                                <div class="relative">
                                    <div
                                        class="size-14 rounded-full overflow-hidden border-2 border-slate-100 dark:border-slate-800 bg-slate-200 dark:bg-slate-700 flex items-center justify-center">
                                        @if($agent->avatar_path)
                                            <img src="{{ Storage::url($agent->avatar_path) }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <span class="text-2xl font-bold text-slate-500 dark:text-slate-400">
                                                {{ strtoupper(substr($agent->name, 0, 1)) }}
                                            </span>
                                        @endif
                                    </div>
                                    <div
                                        class="absolute -bottom-1 -right-1 bg-green-500 border-2 border-white dark:border-slate-900 size-4 rounded-full">
                                    </div>
                                </div>
                                <button class="text-slate-400 hover:text-red-500 transition-colors">
                                    <span class="material-symbols-outlined text-[22px]">favorite</span>
                                </button>
                            </div>

                            <div>
                                <h3
                                    class="text-lg font-bold text-slate-900 dark:text-white group-hover:text-blue-600 transition-colors">
                                    {{ $agent->name }}
                                </h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-2 mt-1">
                                    {{ $agent->description ?: __('Siap membantu tugas Anda.') }}
                                </p>
                            </div>

                            <div class="flex flex-wrap gap-2 mt-auto pt-2">
                                @if($agent->capabilities)
                                    @foreach($agent->capabilities as $cap)
                                        <span
                                            class="px-2.5 py-1 rounded bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 text-xs font-semibold border border-blue-100 dark:border-blue-500/20">
                                            {{ ucfirst(str_replace('_', ' ', $cap)) }}
                                        </span>
                                    @endforeach
                                @endif
                                <span
                                    class="px-2.5 py-1 rounded bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-xs font-semibold border border-slate-200 dark:border-slate-700">
                                    {{ $agent->openrouter_model_id }}
                                </span>
                            </div>
                        </div>

                        <div
                            class="p-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/50">
                            <div class="flex items-center gap-1 text-slate-500 dark:text-slate-400 text-xs font-medium">
                                <span class="material-symbols-outlined text-[16px] text-yellow-500 filled">star</span>
                                4.9 ({{ rand(10, 500) }})
                            </div>
                            <a href="{{ route('agents.chat', $agent) }}"
                                class="text-sm font-bold text-blue-600 hover:text-blue-700 transition-colors flex items-center gap-1">
                                {{ __('Gunakan Agen') }} <span
                                    class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 text-gray-500">
                        <p class="text-xl mb-2">{{ __('Tidak ada agen tersedia') }}</p>
                        <p>{{ __('Coba lagi nanti atau hubungi administrator.') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
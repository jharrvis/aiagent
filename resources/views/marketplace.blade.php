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
                        <h1 class="text-3xl md:text-4xl font-bold text-white mb-3">{{ __('Asisten CEO') }}
                        </h1>
                        <p class="text-slate-400 text-lg">
                            {{ __('Asisten CEO AI untuk membantu anda meningkatkan alur kerja Anda. Dari asisten analis hingga penulis kreatif.') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($agents ?? \App\Models\Agent::where('is_active', true)->get() as $agent)
                    <a href="{{ route('agents.chat', $agent) }}"
                        class="group flex flex-col bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden hover:border-blue-500/50 hover:shadow-xl hover:shadow-blue-500/5 transition-all duration-300 cursor-pointer">
                        <div class="p-6 flex flex-col gap-4 flex-1">
                            <div class="flex justify-between items-start">
                                <div class="relative">
                                    <div
                                        class="size-20 rounded-full overflow-hidden border-3 border-slate-100 dark:border-slate-800 bg-slate-200 dark:bg-slate-700 flex items-center justify-center">
                                        @if($agent->avatar_path)
                                            <img src="{{ Storage::url($agent->avatar_path) }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <span class="text-3xl font-bold text-slate-500 dark:text-slate-400">
                                                {{ strtoupper(substr($agent->name, 0, 1)) }}
                                            </span>
                                        @endif
                                    </div>
                                    <div
                                        class="absolute -bottom-1 -right-1 bg-green-500 border-2 border-white dark:border-slate-900 size-4 rounded-full">
                                    </div>
                                </div>
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
                            </div>
                        </div>
                    </a>
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
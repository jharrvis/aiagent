<x-app-layout>
    <div class="min-h-screen text-slate-900 dark:text-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            <!-- Welcome Header -->
            <div class="mb-12">
                <h1
                    class="text-4xl font-bold bg-gradient-to-r from-blue-400 to-indigo-400 bg-clip-text text-transparent mb-2">
                    Selamat Datang, {{ Auth::user()->name }}! 🚀
                </h1>
                <p class="text-slate-400 text-lg">Inilah ringkasan aktivitas AI Anda hari ini.</p>
            </div>

            <!-- Quick Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                <div
                    class="bg-white dark:bg-slate-800/50 backdrop-blur-xl border border-slate-200 dark:border-slate-700/50 p-6 rounded-3xl group hover:border-blue-500/30 transition-all duration-300 shadow-sm">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="p-3 bg-blue-500/10 rounded-2xl text-blue-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5z" />
                            </svg>
                        </div>
                        <span class="text-slate-400 font-medium">Total Pesan</span>
                    </div>
                    <div class="text-3xl font-bold">{{ number_format($stats['total_messages']) }}</div>
                </div>

                <div
                    class="bg-white dark:bg-slate-800/50 backdrop-blur-xl border border-slate-200 dark:border-slate-700/50 p-6 rounded-3xl group hover:border-purple-500/30 transition-all duration-300 shadow-sm">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="p-3 bg-purple-500/10 rounded-2xl text-purple-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <span class="text-slate-400 font-medium">Gambar Dibuat</span>
                    </div>
                    <div class="text-3xl font-bold">{{ number_format($stats['total_images']) }}</div>
                </div>

                <div
                    class="bg-white dark:bg-slate-800/50 backdrop-blur-xl border border-slate-200 dark:border-slate-700/50 p-6 rounded-3xl group hover:border-emerald-500/30 transition-all duration-300 shadow-sm">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="p-3 bg-emerald-500/10 rounded-2xl text-emerald-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <span class="text-slate-400 font-medium">Agen Aktif</span>
                    </div>
                    <div class="text-3xl font-bold">{{ number_format($stats['active_agents']) }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

                <!-- Recent Conversations -->
                <div class="lg:col-span-1">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold">Lanjutkan Chat</h2>
                        <a href="{{ route('conversations.index') }}"
                            class="text-blue-400 text-sm hover:underline font-medium">Semua</a>
                    </div>

                    <div class="space-y-4">
                        @foreach($recentConversations as $conv)
                            <a href="{{ route('conversations.show', $conv) }}"
                                class="flex items-center gap-4 p-4 bg-white dark:bg-slate-800/30 hover:bg-slate-50 dark:hover:bg-slate-800/60 border border-slate-200 dark:border-slate-700/50 rounded-2xl transition-all group shadow-sm">
                                <div class="relative">
                                    @if($conv->agent->avatar)
                                        <img src="{{ Storage::disk('public')->url($conv->agent->avatar) }}"
                                            class="w-12 h-12 rounded-xl object-cover ring-2 ring-slate-700/50 group-hover:ring-blue-500/50 transition-all shadow-lg"
                                            alt="{{ $conv->agent->name }}">
                                    @else
                                        <div
                                            class="w-12 h-12 rounded-xl bg-slate-700 flex items-center justify-center text-slate-400">
                                            <span class="material-symbols-outlined">person</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-0.5">
                                        <h3 class="font-semibold text-slate-900 dark:text-slate-200 truncate">
                                            {{ $conv->agent->name }}</h3>
                                        <span
                                            class="text-[10px] text-slate-500">{{ $conv->updated_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-xs text-slate-500 truncate italic">Klik untuk melanjutkan percakapan...
                                    </p>
                                </div>
                            </a>
                        @endforeach
                        @if($recentConversations->isEmpty())
                            <div
                                class="text-center py-10 bg-white dark:bg-slate-800/20 rounded-3xl border border-dashed border-slate-300 dark:border-slate-700">
                                <p class="text-slate-500 text-sm px-4">Belum ada percakapan terbaru.</p>
                                <a href="{{ route('marketplace') }}"
                                    class="inline-block mt-4 text-xs bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-full transition-colors">
                                    Cari Agen
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recommended Agents Grid -->
                <div class="lg:col-span-2">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold">Eksplorasi Agen</h2>
                        <a href="{{ route('marketplace') }}"
                            class="text-blue-400 text-sm hover:underline font-medium">Lihat Lebih Banyak</a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        @foreach($recommendedAgents as $agent)
                            <div
                                class="bg-white dark:bg-slate-800/40 backdrop-blur-md border border-slate-200 dark:border-slate-700/50 rounded-[2rem] overflow-hidden group hover:border-blue-500/30 transition-all duration-300 shadow-sm">
                                <div class="p-6">
                                    <div class="flex items-start gap-4 mb-5">
                                        <div class="relative">
                                            <div
                                                class="absolute inset-0 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl blur-lg opacity-20 group-hover:opacity-40 transition-opacity">
                                            </div>
                                            @if($agent->avatar)
                                                <img src="{{ Storage::disk('public')->url($agent->avatar) }}"
                                                    class="relative w-16 h-16 rounded-2xl object-cover ring-1 ring-slate-700"
                                                    alt="{{ $agent->name }}">
                                            @else
                                                <div
                                                    class="relative w-16 h-16 rounded-2xl bg-slate-700 flex items-center justify-center text-slate-400 ring-1 ring-slate-700">
                                                    <span class="material-symbols-outlined text-3xl">person</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <h3
                                                class="text-lg font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                                {{ $agent->name }}
                                            </h3>
                                            <div class="flex flex-wrap gap-1.5 mt-2">
                                                @foreach($agent->capabilities ?? [] as $cap)
                                                    <span
                                                        class="text-[10px] px-2 py-0.5 bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300 rounded-full border border-slate-200 dark:border-slate-600/50 uppercase tracking-wider font-semibold">
                                                        {{ $cap }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    @if($agent->description)
                                        <p class="text-slate-400 text-xs leading-relaxed mb-6 line-clamp-2">
                                            {{ $agent->description }}
                                        </p>
                                    @endif

                                    <div class="flex gap-3 mt-auto">
                                        <a href="{{ route('agents.chat', $agent) }}"
                                            class="flex-1 bg-blue-600/90 hover:bg-blue-600 text-white text-xs font-bold py-3 rounded-2xl transition-all shadow-lg shadow-blue-500/20 text-center">
                                            Buka Chat
                                        </a>
                                        <button
                                            class="px-3 bg-slate-100 dark:bg-slate-700/50 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-2xl border border-slate-200 dark:border-slate-600/30 transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <div class="min-h-screen bg-slate-50 dark:bg-[#0f172a] text-slate-900 dark:text-slate-200 pb-12">
        <!-- Top Gradient Background -->
        <div
            class="absolute top-0 left-0 right-0 h-64 bg-gradient-to-b from-blue-600/10 to-transparent pointer-events-none">
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative pt-10">
            <!-- Welcome Header -->
            <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <h1 class="text-3xl md:text-4xl font-black tracking-tight text-slate-900 dark:text-white mb-2">
                        Halo, <span
                            class="bg-gradient-to-r from-blue-500 to-indigo-500 bg-clip-text text-transparent">{{ explode(' ', Auth::user()->name)[0] }}</span>!
                        👑
                    </h1>
                    <p class="text-slate-500 dark:text-slate-400 font-medium">
                        Selamat datang kembali di <span
                            class="text-blue-500 font-bold italic underline underline-offset-4 decoration-blue-500/30">Assisten
                            CEO</span>. Siap untuk produktivitas luar biasa hari ini?
                    </p>
                </div>
                <div
                    class="hidden lg:flex items-center gap-3 text-sm font-semibold text-slate-400 bg-white dark:bg-slate-800/50 px-4 py-2 rounded-2xl border border-slate-200 dark:border-slate-700/50 shadow-sm transition-colors">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Sistem Optimal & Aktif
                </div>
            </div>

            <!-- Stats & Insights Row -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-10">
                <!-- Main Stats Column -->
                <div class="lg:col-span-8 flex flex-col gap-6">
                    <!-- Stat Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <!-- Total Pesan -->
                        <div
                            class="bg-white dark:bg-slate-800/40 p-5 rounded-3xl border border-slate-200 dark:border-slate-700/50 hover:border-blue-500/30 transition-all group shadow-sm">
                            <div
                                class="p-2.5 bg-blue-50 dark:bg-blue-500/10 rounded-xl text-blue-600 dark:text-blue-400 w-fit mb-3 group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-[20px]">forum</span>
                            </div>
                            <div class="text-2xl font-black text-slate-900 dark:text-white">
                                {{ number_format($stats['total_messages'] ?? 0) }}
                            </div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Total Pesan
                            </div>
                        </div>

                        <!-- Gambar Dibuat -->
                        <div
                            class="bg-white dark:bg-slate-800/40 p-5 rounded-3xl border border-slate-200 dark:border-slate-700/50 hover:border-purple-500/30 transition-all group shadow-sm">
                            <div
                                class="p-2.5 bg-purple-50 dark:bg-purple-500/10 rounded-xl text-purple-600 dark:text-purple-400 w-fit mb-3 group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-[20px]">palette</span>
                            </div>
                            <div class="text-2xl font-black text-slate-900 dark:text-white">
                                {{ number_format($stats['total_images'] ?? 0) }}
                            </div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Gambar AI
                            </div>
                        </div>

                        <!-- Agen Aktif -->
                        <div
                            class="bg-white dark:bg-slate-800/40 p-5 rounded-3xl border border-slate-200 dark:border-slate-700/50 hover:border-emerald-500/30 transition-all group shadow-sm">
                            <div
                                class="p-2.5 bg-emerald-50 dark:bg-emerald-500/10 rounded-xl text-emerald-600 dark:text-emerald-400 w-fit mb-3 group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-[20px]">smart_toy</span>
                            </div>
                            <div class="text-2xl font-black text-slate-900 dark:text-white">
                                {{ number_format($stats['active_agents'] ?? 0) }}
                            </div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Agen Unik
                            </div>
                        </div>

                        <!-- Saldo Token -->
                        <div
                            class="bg-white dark:bg-slate-800/40 p-5 rounded-3xl border border-slate-200 dark:border-slate-700/50 hover:border-amber-500/30 transition-all group shadow-sm">
                            <div
                                class="p-2.5 bg-amber-50 dark:bg-amber-500/10 rounded-xl text-amber-600 dark:text-amber-400 w-fit mb-3 group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-[20px]">diamond</span>
                            </div>
                            <div class="text-2xl font-black text-slate-900 dark:text-white">
                                @if(Auth::user()->is_admin)
                                    ∞
                                @else
                                    {{ number_format($stats['token_balance'] ?? 0, 0, ',', '.') }}
                                @endif
                            </div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Sisa Token
                            </div>
                        </div>
                    </div>

                    <!-- Usage Chart Card -->
                    <div
                        class="bg-white dark:bg-slate-800/40 p-6 md:p-8 rounded-[2.5rem] border border-slate-200 dark:border-slate-700/50 shadow-sm relative overflow-hidden min-h-[350px]">
                        <div class="flex items-center justify-between mb-6 relative z-10">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight">
                                    Grafik Pemakaian Token</h3>
                                <p class="text-xs text-slate-400 font-medium">Tren aktivitas Anda selama 7 hari terakhir
                                </p>
                            </div>
                            <div class="p-2 bg-slate-50 dark:bg-slate-700/50 rounded-xl transition-colors">
                                <span class="material-symbols-outlined text-slate-400">insights</span>
                            </div>
                        </div>
                        <div class="h-64 relative z-10">
                            <canvas id="usageChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Side Widget Column -->
                <div class="lg:col-span-4 flex flex-col gap-6">
                    <!-- Top Agent Card -->
                    @if($topAgent)
                        <div
                            class="bg-gradient-to-br from-blue-600 to-indigo-700 p-8 rounded-[2.5rem] text-white shadow-2xl relative overflow-hidden group">
                            <div
                                class="absolute top-0 right-0 p-4 opacity-10 group-hover:rotate-12 transition-transform duration-700">
                                <span class="material-symbols-outlined text-[200px]">verified</span>
                            </div>

                            <div class="relative z-10">
                                <span
                                    class="inline-block px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-[10px] font-black uppercase tracking-widest mb-6">Partner
                                    Setia Anda</span>

                                <div class="flex items-center gap-5 mb-6">
                                    <div class="relative">
                                        <div
                                            class="absolute inset-0 bg-white rounded-2xl blur opacity-20 group-hover:opacity-40 transition-opacity">
                                        </div>
                                        @if($topAgent->avatar_path)
                                            <img src="{{ Storage::disk('public')->url($topAgent->avatar_path) }}"
                                                class="relative w-20 h-20 rounded-2xl object-cover ring-4 ring-white/10 group-hover:scale-105 transition-transform"
                                                alt="{{ $topAgent->name }}">
                                        @else
                                            <div
                                                class="relative w-20 h-20 rounded-2xl bg-white/10 flex items-center justify-center text-white ring-4 ring-white/10">
                                                <span class="material-symbols-outlined text-4xl">person</span>
                                            </div>
                                        @endif
                                        <div
                                            class="absolute -bottom-2 -right-2 bg-emerald-500 w-6 h-6 rounded-full flex items-center justify-center border-4 border-indigo-700">
                                            <span class="material-symbols-outlined text-[12px]">flash_on</span>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-black">{{ $topAgent->name }}</h3>
                                        <p class="text-blue-100/70 text-sm font-medium">{{ $topAgent->conversations_count }}
                                            Percakapan</p>
                                    </div>
                                </div>

                                <a href="{{ route('agents.chat', $topAgent) }}"
                                    class="flex items-center justify-center gap-2 w-full py-4 bg-white text-blue-600 rounded-2xl font-black text-sm hover:bg-blue-50 transition-all shadow-xl active:scale-95">
                                    Lanjutkan Sesi
                                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                                </a>
                            </div>
                        </div>
                    @else
                        <div
                            class="bg-white dark:bg-slate-800/40 p-8 rounded-[2.5rem] border border-dashed border-slate-300 dark:border-slate-700 flex flex-col items-center justify-center text-center h-full min-h-[300px]">
                            <div class="p-4 bg-slate-50 dark:bg-slate-700/30 rounded-full mb-4 transition-colors">
                                <span class="material-symbols-outlined text-4xl text-slate-400">person_search</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Cari Partner AI Pertama</h3>
                            <p class="text-xs text-slate-500 mb-6 max-w-[200px]">Temukan asisten AI yang sesuai dengan
                                kebutuhan bisnis atau personal Anda di marketplace.</p>
                            <a href="{{ route('marketplace') }}"
                                class="px-6 py-3 bg-blue-600 text-white rounded-xl font-bold text-xs shadow-lg shadow-blue-500/20">Telusuri
                                Marketplace</a>
                        </div>
                    @endif

                    <!-- User Account Health / Action -->
                    <div
                        class="bg-white dark:bg-slate-800/40 p-6 rounded-[2.5rem] border border-slate-200 dark:border-slate-700/50 shadow-sm transition-colors">
                        <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Quick Links</h4>
                        <div class="grid grid-cols-2 gap-3">
                            <a href="{{ route('profile.edit') }}"
                                class="flex flex-col items-center gap-2 p-4 bg-slate-50 dark:bg-slate-700/30 rounded-2xl hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all border border-transparent hover:border-blue-500/20">
                                <span class="material-symbols-outlined text-blue-500">settings</span>
                                <span class="text-[10px] font-bold">Profil</span>
                            </a>
                            <a href="{{ route('gallery') }}"
                                class="flex flex-col items-center gap-2 p-4 bg-slate-50 dark:bg-slate-700/30 rounded-2xl hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all border border-transparent hover:border-purple-500/20">
                                <span class="material-symbols-outlined text-purple-500">image</span>
                                <span class="text-[10px] font-bold">Galeri</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Grid: Conversations & Marketplace -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 mt-6">
                <!-- Recent Conversations -->
                <div class="lg:col-span-12 xl:col-span-5">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h2 class="text-2xl font-black text-slate-900 dark:text-white">Lanjutkan Chat</h2>
                            <p class="text-xs text-slate-400 font-medium mt-0.5">Riwayat percakapan terbaru Anda</p>
                        </div>
                        <a href="{{ route('conversations.index') }}"
                            class="p-2 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm hover:text-blue-500 transition-all">
                            <span class="material-symbols-outlined text-[20px]">sort</span>
                        </a>
                    </div>

                    <div class="space-y-4">
                        @forelse($recentConversations as $conv)
                            <a href="{{ route('conversations.show', $conv) }}"
                                class="flex items-center gap-4 p-4 bg-white dark:bg-slate-800/20 hover:bg-white dark:hover:bg-slate-800/40 border-2 border-transparent hover:border-blue-500/30 rounded-3xl transition-all group shadow-sm">
                                <div class="relative">
                                    @if($conv->agent->avatar_path)
                                        <img src="{{ Storage::disk('public')->url($conv->agent->avatar_path) }}"
                                            class="w-14 h-14 rounded-2xl object-cover ring-2 ring-slate-100 dark:ring-slate-700/50 group-hover:scale-105 transition-all"
                                            alt="{{ $conv->agent->name }}">
                                    @else
                                        <div
                                            class="w-14 h-14 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 border border-slate-200 dark:border-slate-700">
                                            <span class="material-symbols-outlined text-2xl">person</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-0.5">
                                        <h3
                                            class="font-bold text-slate-900 dark:text-slate-200 truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                            {{ $conv->agent->name }}
                                        </h3>
                                        <span
                                            class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase bg-slate-50 dark:bg-slate-900 px-2 py-0.5 rounded-full">
                                            {{ $conv->updated_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-500 truncate font-medium">Lanjutkan diskusi cerdas Anda...
                                    </p>
                                </div>
                                <div class="opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="material-symbols-outlined text-blue-500">chevron_right</span>
                                </div>
                            </a>
                        @empty
                            <div
                                class="p-8 text-center bg-white dark:bg-slate-800/20 rounded-3xl border border-dashed border-slate-300 dark:border-slate-700 transition-colors">
                                <p class="text-sm text-slate-500 font-medium">Belum ada percakapan terbaru.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Explore Agents -->
                <div class="lg:col-span-12 xl:col-span-7">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h2 class="text-2xl font-black text-slate-900 dark:text-white">Eksplorasi Agen</h2>
                            <p class="text-xs text-slate-400 font-medium mt-0.5">Tingkatkan produktivitas dengan partner
                                terbaik</p>
                        </div>
                        <a href="{{ route('marketplace') }}"
                            class="flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-black transition-all shadow-lg shadow-blue-500/20">
                            Semua
                            <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($recommendedAgents as $agent)
                            <div
                                class="bg-white dark:bg-slate-800/20 p-6 rounded-[2rem] border-2 border-transparent hover:border-blue-500/30 transition-all duration-300 shadow-sm relative overflow-hidden group">
                                <div class="flex items-start gap-4 mb-4">
                                    <div class="relative">
                                        @if($agent->avatar_path)
                                            <img src="{{ Storage::disk('public')->url($agent->avatar_path) }}"
                                                class="relative w-16 h-16 rounded-2xl object-cover shadow-xl group-hover:scale-110 transition-transform duration-500"
                                                alt="{{ $agent->name }}">
                                        @else
                                            <div
                                                class="relative w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 border border-slate-200 dark:border-slate-700">
                                                <span class="material-symbols-outlined text-3xl">person</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-black text-slate-900 dark:text-white truncate">
                                            {{ $agent->name }}
                                        </h3>
                                        <div class="flex flex-wrap gap-1 mt-1">
                                            @foreach(collect($agent->capabilities ?? [])->take(2) as $cap)
                                                <span
                                                    class="text-[8px] font-black px-2 py-0.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full border border-blue-100 dark:border-blue-800 uppercase tracking-tighter">
                                                    {{ $cap }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <p
                                    class="text-slate-500 dark:text-slate-400 text-[11px] leading-relaxed mb-6 line-clamp-2 font-medium">
                                    {{ $agent->description ?? 'Partner AI handal untuk produktivitas Anda.' }}
                                </p>
                                <a href="{{ route('agents.chat', $agent) }}"
                                    class="flex items-center justify-center gap-2 w-full py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-xl font-black text-xs hover:bg-slate-800 dark:hover:bg-slate-100 transition-all">
                                    Buka Chat
                                    <span class="material-symbols-outlined text-[16px]">chat</span>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const ctxUsage = document.getElementById('usageChart').getContext('2d');

                // Premium Gradient for the Chart
                const gradientUsage = ctxUsage.createLinearGradient(0, 0, 0, 300);
                gradientUsage.addColorStop(0, 'rgba(59, 130, 246, 0.4)');
                gradientUsage.addColorStop(1, 'rgba(59, 130, 246, 0.05)');

                const chartData = @json($chartData);

                new Chart(ctxUsage, {
                    type: 'line',
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            label: 'Total Tokens',
                            data: chartData.values,
                            fill: true,
                            backgroundColor: gradientUsage,
                            borderColor: '#3b82f6',
                            borderWidth: 4,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#3b82f6',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                titleFont: { size: 12, weight: 'bold' },
                                bodyFont: { size: 14, weight: 'black' },
                                padding: 12,
                                borderRadius: 12,
                                displayColors: false,
                                callbacks: {
                                    label: function (context) {
                                        return new Intl.NumberFormat('id-ID').format(context.raw) + ' Tokens';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(203, 213, 225, 0.1)',
                                    drawBorder: false
                                },
                                ticks: {
                                    color: '#94a3b8',
                                    font: { size: 10, weight: '600' },
                                    maxTicksLimit: 5,
                                    callback: function (val) {
                                        return val >= 1000 ? (val / 1000).toFixed(1) + 'k' : val;
                                    }
                                }
                            },
                            x: {
                                grid: { display: false },
                                ticks: {
                                    color: '#94a3b8',
                                    font: { size: 10, weight: '600' }
                                }
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
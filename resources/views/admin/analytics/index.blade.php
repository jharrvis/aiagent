<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">
                            {{ __('Analitik & Laporan') }}
                        </h1>
                        <p class="text-slate-500 dark:text-slate-400 mt-1">
                            {{ __('Pantau penggunaan API dan aktivitas model AI secara real-time.') }}
                        </p>
                    </div>
                    <a href="{{ route('admin.dashboard') }}"
                        class="inline-flex items-center gap-2 text-slate-500 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors font-medium">
                        <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                        {{ __('Kembali ke Dashboard') }}
                    </a>
                </div>
            </div>

            @if($activityError)
                <div class="mb-6 p-4 bg-amber-100 dark:bg-amber-900/30 border-l-4 border-amber-500 text-amber-700 dark:text-amber-400 rounded-r-xl shadow-sm">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined mt-1">warning</span>
                        <div>
                            <p class="font-bold">{{ __('Tidak dapat mengambil data penggunaan API') }}</p>
                            <p class="text-sm mt-1">{{ $activityError }}</p>
                            @if(str_contains($activityError, 'Management key not configured'))
                                <div class="mt-3 p-3 bg-amber-50 dark:bg-amber-900/20 rounded-lg border border-amber-200 dark:border-amber-700">
                                    <p class="text-xs font-semibold text-amber-800 dark:text-amber-300 mb-2">{{ __('Solusi / Solution:') }}</p>
                                    <ol class="text-xs text-amber-700 dark:text-amber-400 space-y-1 list-decimal list-inside">
                                        <li>{{ __('Hubungi administrator untuk mengkonfigurasi management API key') }}</li>
                                        <li>{{ __('Pastikan OPENROUTER_MANAGEMENT_KEY sudah diatur di file .env') }}</li>
                                    </ol>
                                    <p class="text-xs mt-2 italic text-amber-600 dark:text-amber-400">{{ __('Management key diperlukan untuk mengakses endpoint aktivitas API.') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Summary Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">{{ __('Total Request') }}</p>
                        <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                            <span class="material-symbols-outlined text-[20px]">api</span>
                        </div>
                    </div>
                    <h4 class="text-2xl font-black text-slate-900 dark:text-white">{{ number_format($summaryStats['total_requests']) }}</h4>
                    <p class="text-xs text-slate-400 mt-1">{{ __('requests') }}</p>
                </div>

                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">{{ __('Total Token') }}</p>
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                            <span class="material-symbols-outlined text-[20px]">token</span>
                        </div>
                    </div>
                    <h4 class="text-2xl font-black text-slate-900 dark:text-white">{{ number_format($summaryStats['total_tokens']) }}</h4>
                    <p class="text-xs text-slate-400 mt-1">
                        {{ __('Prompt:') }} {{ number_format($summaryStats['total_prompt_tokens']) }} | 
                        {{ __('Completion:') }} {{ number_format($summaryStats['total_completion_tokens']) }}
                    </p>
                </div>

                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">{{ __('Pemakaian (USD)') }}</p>
                        <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center text-purple-600 dark:text-purple-400">
                            <span class="material-symbols-outlined text-[20px]">attach_money</span>
                        </div>
                    </div>
                    <h4 class="text-2xl font-black text-slate-900 dark:text-white">${{ number_format($summaryStats['total_usage_usd'], 4) }}</h4>
                    <p class="text-xs text-slate-400 mt-1">Rp {{ number_format($summaryStats['total_usage_idr'], 0, ',', '.') }}</p>
                </div>

                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl shadow-sm {{ ($apiUsage * $multiplier) >= $apiLimit && $apiLimit > 0 ? 'ring-2 ring-red-500' : '' }}">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">{{ __('Tagihan (IDR)') }}</p>
                        <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center text-amber-600 dark:text-amber-400">
                            <span class="material-symbols-outlined text-[20px]">account_balance</span>
                        </div>
                    </div>
                    <h4 class="text-2xl font-black text-emerald-600 dark:text-emerald-400">
                        Rp {{ number_format($apiUsageIdr, 0, ',', '.') }}
                    </h4>
                    <p class="text-xs text-slate-400 mt-1">Limit: Rp {{ number_format($apiLimitIdr, 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Daily Usage Chart -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">{{ __('Penggunaan Harian (30 Hari)') }}</h3>
                    <div class="relative h-48">
                        <canvas id="dailyUsageChart"></canvas>
                    </div>
                </div>

                <!-- Model Usage Chart -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">{{ __('Penggunaan per Model') }}</h3>
                    <div class="relative h-48">
                        <canvas id="modelUsageChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Model Stats Table & Activity Logs Tabs -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm" x-data="{ activeTab: 'stats' }">
                <!-- Tabs Header -->
                <div class="flex border-b border-slate-200 dark:border-slate-700">
                    <button @click="activeTab = 'stats'"
                        :class="activeTab === 'stats' ? 'border-emerald-500 text-emerald-600 dark:text-emerald-400' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300'"
                        class="flex-1 px-6 py-4 text-sm font-bold uppercase tracking-wider border-b-2 transition-all">
                        <span class="flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">bar_chart</span>
                            {{ __('Statistik per Model') }}
                        </span>
                    </button>
                    <button @click="activeTab = 'logs'"
                        :class="activeTab === 'logs' ? 'border-emerald-500 text-emerald-600 dark:text-emerald-400' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300'"
                        class="flex-1 px-6 py-4 text-sm font-bold uppercase tracking-wider border-b-2 transition-all">
                        <span class="flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">list_alt</span>
                            {{ __('Log Aktivitas') }}
                        </span>
                    </button>
                </div>

                <!-- Tab Content: Statistik per Model -->
                <div x-show="activeTab === 'stats'" class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700">
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('Model') }}</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('Provider') }}</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('Request') }}</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('Prompt Tokens') }}</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('Completion Tokens') }}</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('Total Tokens') }}</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('Biaya (USD)') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                                @forelse($modelStats['models'] as $model)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $model['model'] }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-slate-600 dark:text-slate-400">{{ $model['provider'] }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="text-sm font-bold text-slate-900 dark:text-white">{{ number_format($model['requests']) }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="text-sm text-slate-600 dark:text-slate-400">{{ number_format($model['prompt_tokens']) }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="text-sm text-slate-600 dark:text-slate-400">{{ number_format($model['completion_tokens']) }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="text-sm font-bold text-blue-600 dark:text-blue-400">{{ number_format($model['total_tokens']) }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">${{ number_format($model['usage'], 4) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center">
                                            <span class="material-symbols-outlined text-5xl text-slate-300 dark:text-slate-600 mb-3">analytics</span>
                                            <p class="text-slate-500 dark:text-slate-400">{{ __('Tidak ada data penggunaan') }}</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab Content: Log Aktivitas -->
                <div x-show="activeTab === 'logs'" class="p-6">
                    <!-- Filters -->
                    <div class="flex flex-wrap gap-4 items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Filter') }}</h3>
                        
                        <form action="{{ route('admin.analytics.index') }}" method="GET" class="flex flex-wrap gap-3">
                            <input type="date" name="date" value="{{ $selectedDate }}" max="{{ date('Y-m-d') }}" min="{{ date('Y-m-d', strtotime('-30 days')) }}"
                                class="px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none">
                            
                            <select name="model"
                                class="px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none cursor-pointer">
                                <option value="">{{ __('Semua Model') }}</option>
                                @foreach($uniqueModels as $model)
                                    <option value="{{ $model }}" {{ $selectedModel === $model ? 'selected' : '' }}>{{ $model }}</option>
                                @endforeach
                            </select>

                            <button type="submit"
                                class="px-4 py-2 bg-slate-900 dark:bg-white hover:bg-slate-800 dark:hover:bg-slate-100 text-white dark:text-slate-900 rounded-lg text-sm font-semibold transition-all">
                                {{ __('Filter') }}
                            </button>
                            @if($selectedDate || $selectedModel)
                                <a href="{{ route('admin.analytics.index') }}"
                                    class="px-4 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-lg text-sm font-semibold transition-all">
                                    {{ __('Reset') }}
                                </a>
                            @endif
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700">
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('Tanggal') }}</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('Model') }}</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('Provider') }}</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('Request') }}</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('Prompt') }}</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('Completion') }}</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('Reasoning') }}</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('Biaya') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                                @forelse($activityLogs as $log)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $log['date'] }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-slate-700 dark:text-slate-300">{{ $log['model'] }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-slate-600 dark:text-slate-400">{{ $log['provider_name'] ?? 'N/A' }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="text-sm font-bold text-slate-900 dark:text-white">{{ number_format($log['requests'] ?? 0) }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="text-sm text-slate-600 dark:text-slate-400">{{ number_format($log['prompt_tokens'] ?? 0) }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="text-sm text-slate-600 dark:text-slate-400">{{ number_format($log['completion_tokens'] ?? 0) }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="text-sm text-slate-600 dark:text-slate-400">{{ number_format($log['reasoning_tokens'] ?? 0) }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">${{ number_format($log['usage'] ?? 0, 4) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-12 text-center">
                                            <span class="material-symbols-outlined text-5xl text-slate-300 dark:text-slate-600 mb-3">list_alt</span>
                                            <p class="text-slate-500 dark:text-slate-400">{{ __('Tidak ada aktivitas ditemukan') }}</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const dailyUsage = @json($dailyUsage);
        const modelStats = @json($modelStats['models']);

        // Daily Usage Chart
        const dailyCtx = document.getElementById('dailyUsageChart').getContext('2d');
        new Chart(dailyCtx, {
            type: 'line',
            data: {
                labels: dailyUsage.map(d => d.date),
                datasets: [{
                    label: 'Requests',
                    data: dailyUsage.map(d => d.requests),
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'y',
                }, {
                    label: 'Usage (USD)',
                    data: dailyUsage.map(d => d.usage),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'y1',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Requests'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false,
                        },
                        title: {
                            display: true,
                            text: 'Usage (USD)'
                        }
                    },
                }
            }
        });

        // Model Usage Chart
        const modelCtx = document.getElementById('modelUsageChart').getContext('2d');
        new Chart(modelCtx, {
            type: 'bar',
            data: {
                labels: modelStats.slice(0, 10).map(m => {
                    const parts = m.model.split('/');
                    return parts[parts.length - 1] || m.model;
                }),
                datasets: [{
                    label: 'Usage (USD)',
                    data: modelStats.slice(0, 10).map(m => m.usage),
                    backgroundColor: 'rgba(16, 185, 129, 0.8)',
                    borderColor: 'rgb(16, 185, 129)',
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Usage (USD)'
                        }
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>

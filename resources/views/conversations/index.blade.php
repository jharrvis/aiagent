<x-app-layout>
    <div class="min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100" 
         x-data="{ 
             searchQuery: '', 
             loading: true,
             deleteConversation(conversationId, element) {
                 if (!confirm('Apakah Anda yakin ingin menghapus percakapan ini secara permanen?')) return;

                 const card = element.closest('.conversation-card');
                 const originalContent = card.innerHTML;

                 // Show loading state on card
                 card.innerHTML = '<div class=\"p-5 space-y-3\"><div class=\"animate-pulse flex items-center gap-4\"><div class=\"w-12 h-12 bg-slate-200 dark:bg-slate-700 rounded-xl\"></div><div class=\"flex-1 space-y-2\"><div class=\"h-4 bg-slate-200 dark:bg-slate-700 rounded w-3/4\"></div><div class=\"h-3 bg-slate-200 dark:bg-slate-700 rounded w-1/2\"></div></div></div></div>';

                 fetch(`/conversations/${conversationId}`, {
                     method: 'DELETE',
                     headers: {
                         'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content,
                         'Accept': 'application/json'
                     }
                 })
                 .then(response => {
                     if (response.ok) {
                         card.style.transition = 'all 0.3s ease';
                         card.style.opacity = '0';
                         card.style.transform = 'scale(0.95)';
                         setTimeout(() => {
                             card.remove();
                             const remaining = document.querySelectorAll('.conversation-card');
                             if (remaining.length === 0) {
                                 location.reload();
                             }
                         }, 300);
                     } else {
                         alert('Gagal menghapus percakapan. Silakan coba lagi.');
                         card.innerHTML = originalContent;
                     }
                 })
                 .catch(error => {
                     console.error('Error:', error);
                     alert('Terjadi kesalahan. Silakan coba lagi.');
                     card.innerHTML = originalContent;
                 });
             }
         }"
         x-init="setTimeout(() => { loading = false }, 500)">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <!-- Header -->
            <div class="mb-8 bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-sm border border-slate-200 dark:border-slate-800">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-xl bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400">
                            <span class="material-symbols-outlined text-[24px]">chat</span>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
                                {{ __('Riwayat Percakapan') }}
                            </h1>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                                {{ __('Lihat dan kelola semua percakapan Anda dengan AI Agent.') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                        <!-- Pencarian -->
                        <div class="relative w-full sm:w-72">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <span class="material-symbols-outlined text-[20px]">search</span>
                            </span>
                            <input type="text" x-model="searchQuery" placeholder="{{ __('Cari percakapan...') }}"
                                class="w-full pl-10 pr-4 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm placeholder:text-slate-500" />
                        </div>

                        <a href="{{ route('marketplace') }}"
                            class="w-full sm:w-auto px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition-all shadow-lg shadow-blue-500/20 flex items-center justify-center gap-2 whitespace-nowrap">
                            <span class="material-symbols-outlined text-[18px]">add</span>
                            {{ __('Chat Baru') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <div class="bg-white dark:bg-slate-900 rounded-xl p-4 shadow-sm border border-slate-200 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-lg bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400">
                            <span class="material-symbols-outlined text-[20px]">chat_bubble</span>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Total Percakapan</p>
                            <p class="text-xl font-bold text-slate-900 dark:text-white">{{ $groupedConversations->sum(fn($g) => $g->count()) }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-900 rounded-xl p-4 shadow-sm border border-slate-200 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-lg bg-emerald-100 dark:bg-emerald-900/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                            <span class="material-symbols-outlined text-[20px]">smart_toy</span>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Agent Aktif</p>
                            <p class="text-xl font-bold text-slate-900 dark:text-white">{{ $groupedConversations->flatMap(fn($g) => $g->pluck('agent_id'))->unique()->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-900 rounded-xl p-4 shadow-sm border border-slate-200 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-lg bg-purple-100 dark:bg-purple-900/20 flex items-center justify-center text-purple-600 dark:text-purple-400">
                            <span class="material-symbols-outlined text-[20px]">today</span>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Hari Ini</p>
                            <p class="text-xl font-bold text-slate-900 dark:text-white">{{ ($groupedConversations['Hari Ini'] ?? collect())->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading Skeleton -->
            <div x-show="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                <template x-for="i in 6">
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
                        <div class="p-5 space-y-4">
                            <div class="animate-pulse flex items-center gap-4">
                                <div class="w-12 h-12 bg-slate-200 dark:bg-slate-700 rounded-xl"></div>
                                <div class="flex-1 space-y-2">
                                    <div class="h-4 bg-slate-200 dark:bg-slate-700 rounded w-3/4"></div>
                                    <div class="flex gap-2">
                                        <div class="h-3 bg-slate-200 dark:bg-slate-700 rounded w-16"></div>
                                        <div class="h-3 bg-slate-200 dark:bg-slate-700 rounded w-12"></div>
                                    </div>
                                    <div class="h-3 bg-slate-200 dark:bg-slate-700 rounded w-full"></div>
                                    <div class="h-3 bg-slate-200 dark:bg-slate-700 rounded w-2/3"></div>
                                </div>
                            </div>
                        </div>
                        <div class="px-5 pb-4 pt-3 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
                            <div class="animate-pulse flex justify-between items-center">
                                <div class="flex gap-2">
                                    <div class="h-8 bg-slate-200 dark:bg-slate-700 rounded-lg w-20"></div>
                                    <div class="h-8 bg-slate-200 dark:bg-slate-700 rounded-lg w-20"></div>
                                </div>
                                <div class="h-8 bg-slate-200 dark:bg-slate-700 rounded-lg w-24"></div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- List -->
            <div x-show="!loading" style="display: none;">
                @forelse($groupedConversations ?? [] as $dateLabel => $group)
                    @php
                        $searchData = $group->map(function($c) {
                            return [
                                'name' => strtolower($c->agent->name),
                                'msg' => strtolower(substr($c->messages->last()->content ?? '', 0, 100))
                            ];
                        })->values()->all();
                    @endphp
                    <div class="mb-8" x-data='{
                        searchData: @json($searchData),
                        get groupMatches() {
                            if ($parent.searchQuery === "") return true;
                            const sq = $parent.searchQuery.toLowerCase();
                            return this.searchData.some(i => i.name.includes(sq) || i.msg.includes(sq));
                        }
                    }' x-show="groupMatches" style="display: none;">

                        <div class="flex items-center gap-2 mb-4">
                            <h2 class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide">
                                {{ $dateLabel }}
                            </h2>
                            <div class="flex-1 h-px bg-slate-200 dark:bg-slate-800"></div>
                            <span class="text-xs text-slate-400 font-medium">{{ $group->count() }} percakapan</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($group as $conv)
                                @php
                                    $agentName = strtolower($conv->agent->name);
                                    $lastMsg = substr($conv->messages->last()->content ?? '', 0, 100);
                                    $lastMessage = $conv->messages->last();
                                    $isUser = $lastMessage?->role === 'user';
                                    // Escape for JavaScript
                                    $escapedAgentName = str_replace(["\\", "'", "\n", "\r"], ["\\\\", "\\'", "\\n", "\\r"], $agentName);
                                    $escapedMsg = str_replace(["\\", "'", "\n", "\r", '"'], ["\\\\", "\\'", "\\n", "\\r", '\"'], $lastMsg);
                                @endphp
                                <div class="conversation-card group bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/80 border border-slate-200 dark:border-slate-800 hover:border-blue-300 dark:hover:border-blue-700 rounded-2xl transition-all duration-200 overflow-hidden shadow-sm hover:shadow-md"
                                     x-data="{
                                         name: '{{ $escapedAgentName }}',
                                         msg: '{{ $escapedMsg }}',
                                         get matches() {
                                             if ($parent.searchQuery === '') return true;
                                             const sq = $parent.searchQuery.toLowerCase();
                                             return this.name.includes(sq) || this.msg.includes(sq);
                                         }
                                     }"
                                     x-show="matches">

                                    <a href="{{ route('conversations.show', $conv) }}" class="block p-5">
                                        <div class="flex items-start gap-4">
                                            <div class="relative shrink-0">
                                                @if($conv->agent->avatar_path)
                                                    <img src="{{ Storage::disk('public')->url($conv->agent->avatar_path) }}"
                                                        class="w-12 h-12 rounded-xl object-cover ring-2 ring-slate-200 dark:ring-slate-700 group-hover:ring-blue-500 transition-all"
                                                        alt="{{ $conv->agent->name }}">
                                                @else
                                                    <div
                                                        class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 group-hover:bg-blue-100 dark:group-hover:bg-blue-900/20 group-hover:text-blue-600 transition-colors">
                                                        <span class="material-symbols-outlined text-[24px]">smart_toy</span>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between gap-2 mb-1">
                                                    <h3 class="font-semibold text-slate-900 dark:text-white truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                                        {{ $conv->agent->name }}
                                                    </h3>
                                                    <span class="text-xs text-slate-400 whitespace-nowrap">
                                                        {{ $conv->updated_at->diffForHumans(short: true) }}
                                                    </span>
                                                </div>
                                                <div class="flex items-center gap-2 mb-2">
                                                    <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-[10px] font-medium rounded-md uppercase">
                                                        {{ $isUser ? 'Anda' : 'AI' }}
                                                    </span>
                                                    <span class="text-xs text-slate-400">
                                                        {{ $conv->updated_at->format('H:i') }}
                                                    </span>
                                                </div>
                                                <p class="text-sm text-slate-600 dark:text-slate-400 line-clamp-2 leading-relaxed">
                                                    {{ $lastMessage?->content ?? 'Percakapan baru...' }}
                                                </p>
                                            </div>
                                        </div>
                                    </a>

                                    <!-- Bottom Action Bar -->
                                    <div class="px-5 pb-4 pt-3 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
                                        <div class="flex items-center justify-between gap-3">
                                            <!-- Left: Download & Delete -->
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('conversations.download', $conv) }}"
                                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-emerald-700 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/30 hover:bg-emerald-200 dark:hover:bg-emerald-800 rounded-lg transition-colors"
                                                   title="{{ __('Unduh') }}">
                                                    <span class="material-symbols-outlined text-[16px]">download</span>
                                                    {{ __('Unduh') }}
                                                </a>
                                                <button onclick="deleteConversation({{ $conv->id }}, this)"
                                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-700 dark:text-red-400 bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-800 rounded-lg transition-colors"
                                                   title="{{ __('Hapus') }}">
                                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                                    {{ __('Hapus') }}
                                                </button>
                                            </div>

                                            <!-- Right: Continue Chat -->
                                            <a href="{{ route('conversations.show', $conv) }}"
                                               class="inline-flex items-center gap-1.5 px-4 py-1.5 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors shadow-sm">
                                                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                                {{ __('Lanjutkan') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div
                        class="py-16 text-center bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                        <div
                            class="h-20 w-20 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                            <span class="material-symbols-outlined text-[40px]">chat_bubble_outline</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Belum ada percakapan</h3>
                        <p class="text-slate-500 dark:text-slate-400 mb-6 max-w-md mx-auto px-4">
                            Mulai obrolan pertama Anda dengan mencoba salah satu AI Agent di Marketplace kami.
                        </p>
                        <a href="{{ route('marketplace') }}"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition-all shadow-lg shadow-blue-500/20">
                            <span class="material-symbols-outlined text-[18px]">explore</span>
                            {{ __('Eksplorasi Marketplace') }}
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>

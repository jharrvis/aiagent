<x-app-layout>
    <div class="min-h-screen bg-[#0f172a] text-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            <!-- Header -->
            <div class="mb-12 flex justify-between items-center">
                <div>
                    <h1
                        class="text-3xl font-bold bg-gradient-to-r from-blue-400 to-indigo-400 bg-clip-text text-transparent mb-2">
                        {{ __('Riwayat Percakapan') }}
                    </h1>
                    <p class="text-slate-400 font-medium">{{ __('Semua obrolan Anda dengan berbagai AI Agent.') }}</p>
                </div>
                <a href="{{ route('marketplace') }}"
                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold transition-all shadow-lg shadow-blue-500/20">
                    + Chat Baru
                </a>
            </div>

            <!-- List -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($conversations as $conv)
                    <a href="{{ route('conversations.show', $conv) }}"
                        class="flex items-start gap-4 p-5 bg-slate-800/30 hover:bg-slate-800/60 border border-slate-700/50 rounded-3xl transition-all group relative overflow-hidden">
                        <div
                            class="absolute inset-0 bg-gradient-to-tr from-blue-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                        </div>

                        <div class="relative shrink-0">
                            @if($conv->agent->avatar)
                                <img src="{{ Storage::disk('public')->url($conv->agent->avatar) }}"
                                    class="w-14 h-14 rounded-2xl object-cover ring-2 ring-slate-700/50 group-hover:ring-blue-500/50 transition-all shadow-lg"
                                    alt="{{ $conv->agent->name }}">
                            @else
                                <div
                                    class="w-14 h-14 rounded-2xl bg-slate-700 flex items-center justify-center text-slate-400 group-hover:bg-slate-600 transition-colors">
                                    <span class="material-symbols-outlined text-3xl">smart_toy</span>
                                </div>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-slate-100 truncate group-hover:text-blue-400 transition-colors mb-1">
                                {{ $conv->agent->name }}</h3>
                            <p class="text-xs text-slate-500 flex items-center gap-1 mb-3">
                                <span class="material-symbols-outlined text-[14px]">calendar_month</span>
                                {{ $conv->updated_at->isoFormat('D MMM YYYY, HH:mm') }}
                            </p>
                            <p class="text-xs text-slate-400 line-clamp-2 italic leading-relaxed">
                                {{ $conv->messages->last()->content ?? 'Percakapan baru...' }}
                            </p>
                        </div>

                        <div
                            class="self-center ml-2 p-2 bg-slate-700/30 rounded-full text-slate-500 group-hover:text-blue-400 group-hover:bg-blue-500/10 transition-all">
                            <span class="material-symbols-outlined">chevron_right</span>
                        </div>
                    </a>
                @empty
                    <div
                        class="col-span-full py-20 text-center bg-slate-800/20 rounded-[3rem] border border-dashed border-slate-700">
                        <div
                            class="h-20 w-20 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-500 border border-slate-700">
                            <span class="material-symbols-outlined text-4xl">chat_bubble_outline</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-300 mb-2">Belum ada percakapan</h3>
                        <p class="text-slate-500 mb-8 px-10">Mulai obrolan pertama Anda dengan mencoba salah satu Agen di
                            Marketplace kami.</p>
                        <a href="{{ route('marketplace') }}"
                            class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-sm font-bold transition-all shadow-lg shadow-blue-500/20">
                            Eksplorasi Marketplace
                        </a>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
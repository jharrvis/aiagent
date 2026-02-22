<x-app-layout>
    <div class="min-h-screen text-slate-900 dark:text-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            <!-- Header -->
            <div class="mb-12 flex justify-between items-center">
                <div>
                    <h1
                        class="text-3xl font-bold bg-gradient-to-r from-emerald-400 to-blue-400 bg-clip-text text-transparent mb-2">
                        {{ __('Galeri Gambar AI') }}
                    </h1>
                    <p class="text-slate-400 font-medium">
                        {{ __('Koleksi keajaiban visual yang Anda buat bersama AI.') }}
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <span
                        class="px-4 py-2 bg-white dark:bg-slate-800/50 rounded-xl border border-slate-200 dark:border-slate-700 text-sm font-semibold text-slate-600 dark:text-slate-300 shadow-sm">
                        {{ $messages->total() }} Item
                    </span>
                </div>
            </div>

            <!-- Gallery Grid -->
            @if($messages->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($messages as $msg)
                        <div x-data="{ open: false }"
                            class="group relative bg-white dark:bg-slate-800/20 border border-slate-200 dark:border-slate-700/50 rounded-3xl overflow-hidden shadow-lg hover:shadow-emerald-500/10 transition-all duration-300">
                            <!-- Image Container -->
                            <div class="aspect-square relative overflow-hidden bg-slate-900 cursor-zoom-in"
                                @click="open = true">
                                <img src="{{ $msg->metadata['image_url'] }}"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                    alt="AI Generated Image">
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent opacity-60">
                                </div>

                                <!-- Hover Overlay Actions -->
                                <div
                                    class="absolute inset-0 flex items-center justify-center gap-3 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-950/40 backdrop-blur-sm">
                                    <button @click.stop="open = true"
                                        class="p-3 bg-white/10 hover:bg-white/20 rounded-full text-white backdrop-blur-md transition-all border border-white/20">
                                        <span class="material-symbols-outlined">zoom_in</span>
                                    </button>
                                    <a href="{{ $msg->metadata['image_url'] }}"
                                        download="ai_image_{{ $msg->id }}.png" target="_blank"
                                        class="p-3 bg-emerald-500 hover:bg-emerald-600 rounded-full text-white shadow-lg transition-all">
                                        <span class="material-symbols-outlined">download</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Details -->
                            <div class="p-5">
                                <p
                                    class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest mb-2">
                                    {{ $msg->conversation->agent->name }}
                                </p>
                                <p class="text-sm text-slate-600 dark:text-slate-300 line-clamp-2 leading-relaxed h-10 mb-4">
                                    {{ $msg->conversation->title }}
                                </p>

                                <div class="flex items-center justify-between pt-4 border-t border-slate-700/50">
                                    <span class="text-[10px] text-slate-500 flex items-center gap-1 font-medium italic">
                                        <span class="material-symbols-outlined text-[14px]">calendar_month</span>
                                        {{ $msg->created_at->diffForHumans() }}
                                    </span>
                                    <a href="{{ route('conversations.show', $msg->conversation) }}"
                                        class="text-xs font-bold text-blue-400 hover:text-white transition-colors">
                                        View Chat →
                                    </a>
                                </div>
                            </div>

                            <!-- Modal (Alpine.js) -->
                            <template x-teleport="body">
                                <div x-show="open" @keydown.escape.window="open = false"
                                    class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-slate-950/90 backdrop-blur-lg"
                                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                                    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

                                    <button @click="open = false"
                                        class="absolute top-6 right-6 text-white hover:text-emerald-400 transition-colors">
                                        <span class="material-symbols-outlined text-[40px]">close</span>
                                    </button>

                                    <div class="max-w-5xl w-full max-h-[90vh] flex flex-col items-center gap-6"
                                        @click.away="open = false">
                                        <img :src="'{{ $msg->metadata['image_url'] }}'"
                                            class="max-w-full max-h-[80vh] rounded-2xl object-contain shadow-2xl border border-white/10">

                                        <div class="flex items-center gap-4">
                                            <a href="{{ $msg->metadata['image_url'] }}" download="ai_image_{{ $msg->id }}.png"
                                                target="_blank"
                                                class="flex items-center gap-2 px-6 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-bold shadow-lg shadow-emerald-500/20 transition-all">
                                                <span class="material-symbols-outlined">download</span>
                                                Download Image
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12">
                    {{ $messages->links() }}
                </div>

            @else
                <div
                    class="py-20 text-center bg-white dark:bg-slate-800/20 rounded-[3rem] border border-dashed border-slate-200 dark:border-slate-700 shadow-sm">
                    <div
                        class="h-24 w-24 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-400 dark:text-slate-500 border border-slate-100 dark:border-slate-700">
                        <span class="material-symbols-outlined text-5xl">image_not_supported</span>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-300 mb-2">Belum ada karya seni</h3>
                    <p class="text-slate-500 mb-8 max-w-md mx-auto">Mulai hasilkan gambar menakjubkan dengan meminta agen AI
                        seperti 'Pelukis Impian' untuk menggambar sesuatu.</p>
                    <a href="{{ route('marketplace') }}"
                        class="px-8 py-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-2xl text-sm font-bold transition-all shadow-lg shadow-emerald-500/20">
                        Mulai Berkreasi
                    </a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
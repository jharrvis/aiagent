<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.agents.index') }}" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-all">
                    <span class="material-symbols-outlined text-[24px]">arrow_back</span>
                </a>
                <h2 class="font-semibold text-xl text-slate-800 dark:text-white leading-tight">
                    {{ $agent->name }}
                </h2>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.agents.edit', $agent) }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-bold text-white bg-amber-600 hover:bg-amber-700 rounded-lg shadow-lg shadow-amber-600/20 transition-all">
                    <span class="material-symbols-outlined mr-2 text-[18px]">edit</span>
                    {{ __('Edit Agen') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Kolom Kiri: Detil Agen -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white dark:bg-slate-900 shadow-xl sm:rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
                        <div class="p-8 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 flex flex-col items-center text-center">
                            @if($agent->avatar_path)
                                <img src="{{ Storage::url($agent->avatar_path) }}" class="size-24 rounded-2xl object-cover shadow-xl border-2 border-white dark:border-slate-700 mb-4">
                            @else
                                <div class="size-24 rounded-2xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 border-2 border-white dark:border-slate-700 shadow-xl mb-4">
                                    <span class="material-symbols-outlined text-[48px]">smart_toy</span>
                                </div>
                            @endif
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white">{{ $agent->name }}</h3>
                            <p class="text-xs font-mono text-slate-500 dark:text-slate-400 mt-1 bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded">{{ $agent->openrouter_model_id }}</p>
                        </div>

                        <div class="p-8 space-y-6">
                            <div>
                                <h4 class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2">{{ __('Konfigurasi') }}</h4>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-slate-600 dark:text-slate-400">{{ __('Temperatur') }}</span>
                                        <span class="text-sm font-bold text-slate-900 dark:text-white bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded">{{ $agent->temperature }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-slate-600 dark:text-slate-400">{{ __('Status') }}</span>
                                        @if($agent->is_active)
                                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-md bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                                {{ __('AKTIF') }}
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-md bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                                {{ __('NONAKTIF') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h4 class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2">{{ __('Kemampuan') }}</h4>
                                <div class="flex flex-wrap gap-2">
                                    @forelse($agent->capabilities ?? [] as $cap)
                                        <span class="px-2.5 py-1 text-[11px] font-bold rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-800">
                                            {{ strtoupper($cap) }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-slate-400 italic">None</span>
                                    @endforelse
                                </div>
                            </div>

                            <div class="pt-6 border-t border-slate-100 dark:border-slate-800">
                                <h4 class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-3">{{ __('Prompt Sistem') }}</h4>
                                <div class="bg-slate-50 dark:bg-slate-950 p-4 rounded-xl border border-slate-200 dark:border-slate-800">
                                    <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed italic whitespace-pre-wrap">{{ $agent->system_prompt ?: __('Tidak ada prompt sistem yang diatur.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Basis Pengetahuan -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-slate-900 shadow-xl sm:rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
                        <div class="p-8 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Basis Pengetahuan') }}</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Unggah dokumen untuk memperluas wawasan kecerdasan agen.') }}</p>
                            </div>
                            <div class="size-12 rounded-2xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center text-amber-600 dark:text-amber-400">
                                <span class="material-symbols-outlined text-[28px]">library_books</span>
                            </div>
                        </div>

                        <div class="p-8">
                            @if(session('success'))
                                <div class="mb-6 flex items-center p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl text-emerald-700 dark:text-emerald-400">
                                    <span class="material-symbols-outlined mr-3 text-[20px]">check_circle</span>
                                    <span class="text-sm font-medium">{{ session('success') }}</span>
                                </div>
                            @endif

                            <form action="{{ route('admin.agents.knowledge.upload', $agent) }}" method="POST" enctype="multipart/form-data" class="mb-8">
                                @csrf
                                <div class="flex flex-col sm:flex-row items-center gap-4 p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-800 transition-colors hover:border-blue-500/50">
                                    <input type="file" name="files[]" accept=".pdf,.txt,.docx" multiple required
                                        class="flex-1 text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer">
                                    <button type="submit" class="w-full sm:w-auto px-6 py-2 bg-slate-900 dark:bg-blue-600 hover:bg-slate-800 dark:hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-lg">
                                        {{ __('Unggah Dokumen') }}
                                    </button>
                                </div>
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-2 text-center uppercase tracking-wider">{{ __('Format: PDF, TXT, DOCX (Maks 10MB per file)') }}</p>
                            </form>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800">
                                            <th class="px-4 py-3 pb-4">{{ __('Nama Berkas') }}</th>
                                            <th class="px-4 py-3 pb-4">{{ __('Status') }}</th>
                                            <th class="px-4 py-3 pb-4 text-right">{{ __('Tanggal') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50">
                                        @forelse($agent->knowledgeSources as $source)
                                            <tr class="group hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                                                <td class="px-4 py-4">
                                                    <div class="flex items-center gap-3">
                                                        <span class="material-symbols-outlined text-slate-400">description</span>
                                                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300 truncate max-w-[200px]">{{ $source->original_filename }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4">
                                                    @switch($source->status)
                                                        @case('pending')
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800 uppercase tracking-wider">{{ __('MENUNGGU') }}</span>
                                                            @break
                                                        @case('processing')
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-800 uppercase tracking-wider animate-pulse">{{ __('DIPROSES') }}</span>
                                                            @break
                                                        @case('ready')
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 uppercase tracking-wider">{{ __('SIAP') }}</span>
                                                            @break
                                                        @case('failed')
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800 uppercase tracking-wider">{{ __('GAGAL') }}</span>
                                                            @break
                                                    @endswitch
                                                </td>
                                                <td class="px-4 py-4 text-right">
                                                    <span class="text-xs text-slate-500 dark:text-slate-400">{{ $source->created_at->diffForHumans() }}</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="px-4 py-12 text-center">
                                                    <div class="flex flex-col items-center">
                                                        <span class="material-symbols-outlined text-[48px] text-slate-200 dark:text-slate-800 mb-2">folder_off</span>
                                                        <p class="text-sm text-slate-400 italic">{{ __('Tidak ada dokumen pengetahuan.') }}</p>
                                                    </div>
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
        </div>
    </div>
</x-app-layout>

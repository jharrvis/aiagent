<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-slate-800 dark:text-white leading-tight">
                    {{ __('Kelola Agen AI') }}
                </h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Pantau dan konfigurasikan tenaga kerja kecerdasan buatan Anda.') }}</p>
            </div>
            <a href="{{ route('admin.agents.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-lg shadow-blue-600/20 transition-all">
                <span class="material-symbols-outlined mr-2 text-[20px]">add</span>
                {{ __('Buat Agen Baru') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Berhasil -->
            @if(session('success'))
                <div class="mb-6 flex items-center p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl text-emerald-700 dark:text-emerald-400 shadow-sm">
                    <span class="material-symbols-outlined mr-3">check_circle</span>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Ringkasan Statistik Singkat -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Total Agen') }}</p>
                        <p class="text-3xl font-black text-slate-900 dark:text-white mt-1">{{ $agents->count() }}</p>
                    </div>
                    <div class="size-12 rounded-2xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                        <span class="material-symbols-outlined text-[28px]">smart_toy</span>
                    </div>
                </div>
                <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Agen Aktif') }}</p>
                        <p class="text-3xl font-black text-emerald-600 dark:text-emerald-400 mt-1">{{ $agents->where('is_active', true)->count() }}</p>
                    </div>
                    <div class="size-12 rounded-2xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <span class="material-symbols-outlined text-[28px]">check_circle</span>
                    </div>
                </div>
                <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Basis Pengetahuan') }}</p>
                        <p class="text-3xl font-black text-amber-600 dark:text-amber-400 mt-1">{{ $agents->sum('knowledge_sources_count') }}</p>
                    </div>
                    <div class="size-12 rounded-2xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center text-amber-600 dark:text-amber-400">
                        <span class="material-symbols-outlined text-[28px]">library_books</span>
                    </div>
                </div>
            </div>

            <!-- Tabel Agen -->
            <div class="bg-white dark:bg-slate-900 shadow-xl overflow-hidden sm:rounded-2xl border border-slate-200 dark:border-slate-800">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800">
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Nama Agen') }}</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Model') }}</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Kemampuan') }}</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Status') }}</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right">{{ __('Aksi') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            @forelse($agents as $agent)
                                <tr class="group hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($agent->avatar_path)
                                                <img src="{{ Storage::url($agent->avatar_path) }}" class="size-10 rounded-xl object-cover shadow-sm">
                                            @else
                                                <div class="size-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-800">
                                                    <span class="material-symbols-outlined">smart_toy</span>
                                                </div>
                                            @endif
                                            <div>
                                                <p class="text-sm font-bold text-slate-900 dark:text-white leading-tight">{{ $agent->name }}</p>
                                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $agent->knowledge_sources_count }} {{ __('dokumen pengetahuan') }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <code class="text-[11px] font-mono bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                            {{ $agent->openrouter_model_id }}
                                        </code>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-1.5">
                                            @forelse($agent->capabilities as $cap)
                                                <span class="px-2 py-0.5 text-[10px] font-bold rounded-md bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                                    {{ strtoupper($cap) }}
                                                </span>
                                            @empty
                                                <span class="text-[11px] text-slate-400 italic">None</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <form action="{{ route('admin.agents.toggle-status', $agent) }}" method="POST" class="inline">
                                            @csrf
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" name="is_active" value="1" {{ $agent->is_active ? 'checked' : '' }} class="sr-only peer" onchange="this.form.submit()">
                                                <div class="w-11 h-6 bg-slate-200 dark:bg-slate-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                                <span class="ml-3 text-sm font-medium text-slate-700 dark:text-slate-300">
                                                    {{ $agent->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                            </label>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.agents.show', $agent) }}" class="p-2 text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-all" title="{{ __('Lihat Detail') }}">
                                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                                            </a>
                                            <a href="{{ route('admin.agents.edit', $agent) }}" class="p-2 text-slate-400 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-all" title="{{ __('Edit Agen') }}">
                                                <span class="material-symbols-outlined text-[20px]">edit</span>
                                            </a>
                                            <form action="{{ route('admin.agents.destroy', $agent) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus agen ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-slate-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-all" title="{{ __('Hapus Agen') }}">
                                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <span class="material-symbols-outlined text-[48px] text-slate-300 mb-2">smart_toy</span>
                                            <p class="text-slate-500 dark:text-slate-400 font-medium">{{ __('Belum ada agen yang dibuat.') }}</p>
                                            <a href="{{ route('admin.agents.create') }}" class="mt-4 text-sm font-bold text-blue-600 dark:text-blue-400 hover:underline">{{ __('Buat agen pertama Anda sekarang') }}</a>
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
</x-app-layout>

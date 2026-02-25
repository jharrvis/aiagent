<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
                        {{ __('Excel Templates') }}
                    </h1>
                    <p class="text-slate-500 dark:text-slate-400 mt-1">
                        {{ __('Kelola template Excel untuk AI agents') }}
                    </p>
                </div>
                <a href="{{ route('admin.excel-templates.create') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-semibold transition-all shadow-lg shadow-emerald-500/20">
                    <span class="material-symbols-outlined">upload_file</span>
                    {{ __('Upload Template') }}
                </a>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-100 dark:bg-emerald-900/30 border-l-4 border-emerald-500 text-emerald-700 dark:text-emerald-400 rounded-r-xl shadow-sm flex items-center gap-3">
                    <span class="material-symbols-outlined">check_circle</span>
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Filter by Agent -->
            <div class="mb-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
                <form action="{{ route('admin.excel-templates.index') }}" method="GET" class="flex gap-3">
                    <select name="agent_id" class="flex-1 px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none cursor-pointer">
                        <option value="">{{ __('All Agents') }}</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}" {{ request('agent_id') == $agent->id ? 'selected' : '' }}>
                                {{ $agent->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-5 py-2.5 bg-slate-900 dark:bg-white hover:bg-slate-800 dark:hover:bg-slate-100 text-white dark:text-slate-900 rounded-xl text-sm font-semibold transition-all">
                        {{ __('Filter') }}
                    </button>
                </form>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700">
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('Name') }}</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('Agent') }}</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('Category') }}</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('Variables') }}</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('Status') }}</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($templates as $template)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div>
                                            <p class="font-semibold text-slate-900 dark:text-white">{{ $template->name }}</p>
                                            <p class="text-xs text-slate-500 mt-1">{{ $template->original_filename }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-slate-600 dark:text-slate-400">{{ $template->agent->name }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($template->category)
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-800">
                                                {{ ucfirst(str_replace('_', ' ', $template->category)) }}
                                            </span>
                                        @else
                                            <span class="text-xs text-slate-400 italic">General</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($template->variables && count($template->variables) > 0)
                                            <div class="flex flex-wrap gap-1">
                                                @foreach(array_slice($template->variables, 0, 5) as $var)
                                                    <span class="px-2 py-0.5 text-xs bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded">
                                                        {{ $var }}
                                                    </span>
                                                @endforeach
                                                @if(count($template->variables) > 5)
                                                    <span class="px-2 py-0.5 text-xs text-slate-400">+{{ count($template->variables) - 5 }} more</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-xs text-slate-400 italic">No variables</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($template->is_active)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.excel-templates.download', $template) }}"
                                                class="p-2 text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-all"
                                                title="{{ __('Download') }}">
                                                <span class="material-symbols-outlined text-[20px]">download</span>
                                            </a>
                                            <a href="{{ route('admin.excel-templates.edit', $template) }}"
                                                class="p-2 text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-all"
                                                title="{{ __('Edit') }}">
                                                <span class="material-symbols-outlined text-[20px]">edit</span>
                                            </a>
                                            <form action="{{ route('admin.excel-templates.destroy', $template) }}"
                                                method="POST"
                                                class="inline"
                                                onsubmit="return confirm('{{ __('Are you sure you want to delete this template?') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 text-slate-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all"
                                                    title="{{ __('Delete') }}">
                                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <span class="material-symbols-outlined text-5xl text-slate-300 dark:text-slate-600 mb-3">table_chart</span>
                                            <p class="text-slate-500 dark:text-slate-400 font-medium">
                                                {{ __('No templates uploaded yet') }}
                                            </p>
                                            <a href="{{ route('admin.excel-templates.create') }}"
                                                class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-sm font-semibold transition-all">
                                                <span class="material-symbols-outlined text-[18px]">upload_file</span>
                                                {{ __('Upload First Template') }}
                                            </a>
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

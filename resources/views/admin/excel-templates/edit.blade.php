<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">
                    {{ __('Edit Excel Template') }}
                </h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1">
                    {{ __('Update template Excel dan konfigurasinya.') }}
                </p>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm p-8">
                <form action="{{ route('admin.excel-templates.update', $excelTemplate) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Agent Selection -->
                    <div class="mb-6">
                        <label for="agent_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                            {{ __('Assign to Agent') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="agent_id" id="agent_id" required
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none">
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" {{ old('agent_id', $excelTemplate->agent_id) == $agent->id ? 'selected' : '' }}>
                                    {{ $agent->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Template Name -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                            {{ __('Template Name') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                            name="name"
                            id="name"
                            value="{{ old('name', $excelTemplate->name) }}"
                            required
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none">
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                            {{ __('Description') }}
                        </label>
                        <textarea name="description"
                            id="description"
                            rows="3"
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none resize-none">{{ old('description', $excelTemplate->description) }}</textarea>
                    </div>

                    <!-- Excel File (Optional) -->
                    <div class="mb-6">
                        <label for="file" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                            {{ __('Excel File') }}
                        </label>
                        <div class="mb-3">
                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                {{ __('Current file:') }} <span class="font-medium">{{ $excelTemplate->original_filename }}</span>
                            </p>
                        </div>
                        <input type="file"
                            name="file"
                            id="file"
                            accept=".xlsx,.xlsm,.xltx"
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none">
                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                            {{ __('Leave empty to keep current file') }} | {{ __('Upload new file to replace') }}
                        </p>
                    </div>

                    <!-- Category -->
                    <div class="mb-6">
                        <label for="category" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                            {{ __('Category') }}
                        </label>
                        <input type="text"
                            name="category"
                            id="category"
                            value="{{ old('category', $excelTemplate->category) }}"
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none">
                    </div>

                    <!-- Variables -->
                    <div class="mb-6">
                        <label for="variables" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                            {{ __('Variables') }}
                        </label>
                        <textarea name="variables"
                            id="variables"
                            rows="4"
                            placeholder='["omzet", "profit_percent", "opex_percent"]'
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none font-mono text-sm">{{ old('variables', is_array($excelTemplate->variables) ? json_encode($excelTemplate->variables, JSON_PRETTY_PRINT) : '') }}</textarea>
                    </div>

                    <!-- Sort Order -->
                    <div class="mb-6">
                        <label for="sort_order" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                            {{ __('Sort Order') }}
                        </label>
                        <input type="number"
                            name="sort_order"
                            id="sort_order"
                            value="{{ old('sort_order', $excelTemplate->sort_order) }}"
                            min="0"
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none">
                    </div>

                    <!-- Is Active -->
                    <div class="mb-8">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox"
                                name="is_active"
                                value="1"
                                {{ old('is_active', $excelTemplate->is_active) ? 'checked' : '' }}
                                class="w-5 h-5 rounded border-slate-300 dark:border-slate-600 text-emerald-600 focus:ring-emerald-500 bg-slate-50 dark:bg-slate-800">
                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                                {{ __('Active') }}
                            </span>
                        </label>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-3 pt-6 border-t border-slate-200 dark:border-slate-700">
                        <a href="{{ route('admin.excel-templates.index') }}"
                            class="px-6 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl font-semibold transition-all">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit"
                            class="px-6 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-semibold transition-all shadow-lg shadow-emerald-500/20">
                            {{ __('Update Template') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">
                    {{ __('Upload Excel Template') }}
                </h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1">
                    {{ __('Upload template Excel yang akan digunakan AI agent untuk generate laporan otomatis.') }}
                </p>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm p-8">
                <form action="{{ route('admin.excel-templates.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Agent Selection -->
                    <div class="mb-6">
                        <label for="agent_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                            {{ __('Assign to Agent') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="agent_id" id="agent_id" required
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition-all @error('agent_id') border-red-500 @enderror">
                            <option value="">{{ __('Select Agent') }}</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" {{ old('agent_id') == $agent->id ? 'selected' : '' }}>
                                    {{ $agent->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('agent_id')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Template Name -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                            {{ __('Template Name') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                            name="name"
                            id="name"
                            value="{{ old('name') }}"
                            required
                            placeholder="e.g., Profit First Calculator"
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition-all @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                            {{ __('Description') }}
                        </label>
                        <textarea name="description"
                            id="description"
                            rows="3"
                            placeholder="{{ __('Describe what this template is for...') }}"
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition-all resize-none @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Excel File -->
                    <div class="mb-6">
                        <label for="file" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                            {{ __('Excel File') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center gap-4">
                            <input type="file"
                                name="file"
                                id="file"
                                accept=".xlsx,.xlsm,.xltx"
                                required
                                class="flex-1 px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition-all @error('file') border-red-500 @enderror">
                        </div>
                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                            {{ __('Supported formats: .xlsx, .xlsm, .xltx') }} | {{ __('Max size: 10MB') }}
                        </p>
                        @error('file')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div class="mb-6">
                        <label for="category" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                            {{ __('Category') }}
                        </label>
                        <input type="text"
                            name="category"
                            id="category"
                            value="{{ old('category') }}"
                            placeholder="e.g., profit_first, franchise, inventory, financial"
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition-all @error('category') border-red-500 @enderror">
                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                            {{ __('Used for organizing and filtering templates') }}
                        </p>
                        @error('category')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Variables -->
                    <div class="mb-6">
                        <label for="variables" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                            {{ __('Variables') }}
                        </label>
                        <textarea name="variables"
                            id="variables"
                            rows="4"
                            placeholder='Example: ["omzet", "profit_percent", "opex_percent", "owner_pay_percent"]'
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition-all resize-none font-mono text-sm @error('variables') border-red-500 @enderror">{{ old('variables') }}</textarea>
                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                            {{ __('Comma-separated list or JSON array of variable names that will be filled in the Excel template') }}
                        </p>
                        @error('variables')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sort Order -->
                    <div class="mb-6">
                        <label for="sort_order" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                            {{ __('Sort Order') }}
                        </label>
                        <input type="number"
                            name="sort_order"
                            id="sort_order"
                            value="{{ old('sort_order', 0) }}"
                            min="0"
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition-all @error('sort_order') border-red-500 @enderror">
                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                            {{ __('Lower numbers appear first when multiple templates are available') }}
                        </p>
                        @error('sort_order')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Is Active -->
                    <div class="mb-8">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox"
                                name="is_active"
                                value="1"
                                {{ old('is_active', true) ? 'checked' : '' }}
                                class="w-5 h-5 rounded border-slate-300 dark:border-slate-600 text-emerald-600 focus:ring-emerald-500 bg-slate-50 dark:bg-slate-800">
                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                                {{ __('Active') }}
                            </span>
                        </label>
                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400 ml-8">
                            {{ __('Inactive templates will not be available for AI to use') }}
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-3 pt-6 border-t border-slate-200 dark:border-slate-700">
                        <a href="{{ route('admin.excel-templates.index') }}"
                            class="px-6 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl font-semibold transition-all">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit"
                            class="px-6 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-semibold transition-all shadow-lg shadow-emerald-500/20">
                            {{ __('Upload Template') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

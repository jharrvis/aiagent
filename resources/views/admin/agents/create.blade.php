<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.agents.index') }}"
                class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-all">
                <span class="material-symbols-outlined text-[24px]">arrow_back</span>
            </a>
            <h2 class="font-semibold text-xl text-slate-800 dark:text-white leading-tight">
                {{ __('Buat Agen Baru') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-slate-900 shadow-xl sm:rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
                <div class="p-8 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Detail Personil AI') }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                        {{ __('Tentukan identitas dan perilaku agen kecerdasan buatan Anda.') }}
                    </p>
                </div>

                <form action="{{ route('admin.agents.store') }}" method="POST" enctype="multipart/form-data"
                    class="p-8 space-y-8">
                    @csrf

                    <!-- Section: Identitas -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label for="name"
                                class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('Nama Agen') }}
                                <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                class="w-full px-4 py-2.5 bg-white dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all shadow-sm"
                                placeholder="{{ __('Contoh: Asisten Pemasaran') }}">
                            <x-input-error :messages="$errors->get('name')" class="mt-1" />
                        </div>

                        <div>
                            <label for="avatar"
                                class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('Foto Profil / Avatar') }}</label>
                            <div class="flex items-center gap-4">
                                <div
                                    class="size-12 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 border border-slate-200 dark:border-slate-700">
                                    <span class="material-symbols-outlined text-[28px]">add_a_photo</span>
                                </div>
                                <input type="file" id="avatar" name="avatar" accept="image/*"
                                    class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-slate-800 dark:file:text-slate-300">
                            </div>
                            <x-input-error :messages="$errors->get('avatar')" class="mt-1" />
                        </div>
                    </div>

                    <!-- Section: Konfigurasi Model -->
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-6 border-t border-slate-100 dark:border-slate-800">
                        <div>
                            <label for="openrouter_model_id"
                                class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('Model AI') }}</label>
                            <select id="openrouter_model_id" name="openrouter_model_id"
                                class="w-full px-4 py-2.5 bg-white dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all shadow-sm">
                                @foreach(config('services.openrouter.models') as $id => $name)
                                    <option value="{{ $id }}" {{ old('openrouter_model_id') === $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="temperature"
                                class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('Temperatur (Kreativitas)') }}</label>
                            <div class="px-2">
                                <input type="range" id="temperature" name="temperature" min="0" max="2" step="0.1"
                                    value="{{ old('temperature', 0.7) }}"
                                    class="w-full h-2 bg-slate-200 dark:bg-slate-800 rounded-lg appearance-none cursor-pointer accent-blue-600"
                                    oninput="document.getElementById('temp-value').textContent = this.value">
                                <div
                                    class="flex justify-between text-[10px] font-bold text-slate-500 dark:text-slate-400 mt-2 uppercase tracking-tighter">
                                    <span>{{ __('Presisi (0)') }}</span>
                                    <span id="temp-value"
                                        class="text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-2 py-0.5 rounded text-xs">{{ old('temperature', 0.7) }}</span>
                                    <span>{{ __('Kreatif (2)') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Perilaku & Deskripsi -->
                    <div class="pt-6 border-t border-slate-100 dark:border-slate-800 space-y-6">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label for="description"
                                    class="block text-sm font-bold text-slate-700 dark:text-slate-300">{{ __('Deskripsi Profil Agen (Tentang)') }}
                                    <span class="text-red-500">*</span></label>
                            </div>
                            <textarea id="description" name="description" rows="3" required
                                class="w-full px-4 py-3 bg-white dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all shadow-sm"
                                placeholder="{{ __('Berikan penjelasan singkat tentang identitas agen ini yang akan tampil di sidebar chat...') }}">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-1" />
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label for="system_prompt"
                                    class="block text-sm font-bold text-slate-700 dark:text-slate-300">{{ __('Prompt Sistem (Instruksi)') }}
                                    <span class="text-red-500">*</span></label>
                                <span
                                    class="text-[10px] font-bold text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded uppercase tracking-wider">{{ __('Mendukung Markdown') }}</span>
                            </div>
                            <textarea id="system_prompt" name="system_prompt" rows="6" required
                                class="w-full px-4 py-3 bg-white dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 font-mono text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all shadow-sm"
                                placeholder="{{ __('Berikan instruksi detail kepada agen tentang peran, gaya bicara, dan batasannya...') }}">{{ old('system_prompt') }}</textarea>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 italic">
                                {{ __('Instruksi ini akan mendefinisikan kepribadian dan cara kerja agen Anda.') }}
                            </p>
                            <x-input-error :messages="$errors->get('system_prompt')" class="mt-1" />
                        </div>
                    </div>

                    <!-- Section: Kemampuan -->
                    <div class="pt-6 border-t border-slate-100 dark:border-slate-800">
                        <label
                            class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-4">{{ __('Fitur & Kemampuan') }}</label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <!-- Text -->
                            <label
                                class="relative flex items-center p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/30 cursor-pointer hover:border-blue-500/50 transition-all group">
                                <div class="flex h-5 items-center">
                                    <input type="checkbox" name="capabilities[]" value="text" checked
                                        class="h-5 w-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 bg-white dark:bg-slate-950 transition-all">
                                </div>
                                <div class="ml-3">
                                    <span
                                        class="block text-sm font-bold text-slate-900 dark:text-white">{{ __('Obrolan Teks') }}</span>
                                    <p
                                        class="text-[10px] text-slate-500 dark:text-slate-400 uppercase tracking-tighter">
                                        {{ __('Dasar interaksi') }}
                                    </p>
                                </div>
                            </label>

                            <!-- Image -->
                            <label
                                class="relative flex items-center p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/30 cursor-pointer hover:border-blue-500/50 transition-all group">
                                <div class="flex h-5 items-center">
                                    <input type="checkbox" name="capabilities[]" value="image"
                                        class="h-5 w-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 bg-white dark:bg-slate-950 transition-all">
                                </div>
                                <div class="ml-3">
                                    <span
                                        class="block text-sm font-bold text-slate-900 dark:text-white">{{ __('Generasi Gambar') }}</span>
                                    <p
                                        class="text-[10px] text-slate-500 dark:text-slate-400 uppercase tracking-tighter">
                                        {{ __('Gunakan DALL-E 3') }}
                                    </p>
                                </div>
                            </label>

                            <!-- PDF -->
                            <label
                                class="relative flex items-center p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/30 cursor-pointer hover:border-blue-500/50 transition-all group">
                                <div class="flex h-5 items-center">
                                    <input type="checkbox" name="capabilities[]" value="pdf"
                                        class="h-5 w-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 bg-white dark:bg-slate-950 transition-all">
                                </div>
                                <div class="ml-3">
                                    <span
                                        class="block text-sm font-bold text-slate-900 dark:text-white">{{ __('Ekspor PDF') }}</span>
                                    <p
                                        class="text-[10px] text-slate-500 dark:text-slate-400 uppercase tracking-tighter">
                                        {{ __('Buat laporan PDF') }}
                                    </p>
                                </div>
                            </label>
                        </div>

                        <!-- Advanced Features -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                            <!-- Excel Generation -->
                            <label
                                class="relative flex items-center p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/30 cursor-pointer hover:border-emerald-500/50 transition-all group">
                                <div class="flex h-5 items-center">
                                    <input type="checkbox" name="can_generate_excel" value="1"
                                        class="h-5 w-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 bg-white dark:bg-slate-950 transition-all">
                                </div>
                                <div class="ml-3 flex-1">
                                    <span
                                        class="block text-sm font-bold text-slate-900 dark:text-white">{{ __('Generasi Excel') }}</span>
                                    <p
                                        class="text-[10px] text-slate-500 dark:text-slate-400 uppercase tracking-tighter">
                                        {{ __('Profit First calculator') }}
                                    </p>
                                </div>
                                <span
                                    class="material-symbols-outlined text-[24px] text-emerald-500">table_chart</span>
                            </label>

                            <!-- File Analysis -->
                            <label
                                class="relative flex items-center p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/30 cursor-pointer hover:border-purple-500/50 transition-all group">
                                <div class="flex h-5 items-center">
                                    <input type="checkbox" name="can_analyze_files" value="1"
                                        class="h-5 w-5 rounded border-slate-300 text-purple-600 focus:ring-purple-500 bg-white dark:bg-slate-950 transition-all">
                                </div>
                                <div class="ml-3 flex-1">
                                    <span
                                        class="block text-sm font-bold text-slate-900 dark:text-white">{{ __('Analisis File') }}</span>
                                    <p
                                        class="text-[10px] text-slate-500 dark:text-slate-400 uppercase tracking-tighter">
                                        {{ __('PDF, Word, Excel, Gambar') }}
                                    </p>
                                </div>
                                <span
                                    class="material-symbols-outlined text-[24px] text-purple-500">attach_file</span>
                            </label>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-3">
                            <span class="font-semibold text-purple-600 dark:text-purple-400">Analisis File:</span>
                            {{ __('Mengizinkan pengguna mengupload dokumen (PDF, Word, Excel, TXT, Gambar) untuk dianalisis oleh agen ini. Menggunakan model AI yang sesuai untuk ekstraksi dan analisis konten.') }}
                        </p>
                    </div>

                    <!-- Submit Area -->
                    <div class="pt-8 flex items-center justify-end gap-4">
                        <a href="{{ route('admin.agents.index') }}"
                            class="px-6 py-2.5 text-sm font-bold text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-white transition-all">
                            {{ __('Batal') }}
                        </a>
                        <button type="submit"
                            class="px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-600/20 transition-all">
                            {{ __('Terbitkan Agen AI') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.agents.index') }}"
                class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-all">
                <span class="material-symbols-outlined text-[24px]">arrow_back</span>
            </a>
            <h2 class="font-semibold text-xl text-slate-800 dark:text-white leading-tight">
                {{ __('Edit Agen: ') . $agent->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-slate-900 shadow-xl sm:rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
                <div class="p-6 sm:p-8 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Detail Personil AI') }}</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                            {{ __('Perbarui identitas dan perilaku agen kecerdasan buatan Anda.') }}
                        </p>
                    </div>
                    <div class="flex items-center gap-3" x-data="{ active: {{ old('is_active', $agent->is_active) ? 'true' : 'false' }} }">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" x-model="active" {{ old('is_active', $agent->is_active) ? 'checked' : '' }} class="sr-only peer">
                            <div class="relative w-12 h-7 bg-slate-200 dark:bg-slate-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                            <span class="ms-3 text-sm font-bold text-slate-700 dark:text-slate-300" x-text="active ? 'Aktif' : 'Nonaktif'">
                                {{ old('is_active', $agent->is_active) ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </label>
                    </div>
                </div>

                <form action="{{ route('admin.agents.update', $agent) }}" method="POST" enctype="multipart/form-data"
                    class="p-8 space-y-8">
                    @csrf
                    @method('PUT')

                    <!-- Section: Identitas -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label for="name"
                                class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('Nama Agen') }}
                                <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name', $agent->name) }}" required
                                class="w-full px-4 py-2.5 bg-white dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all shadow-sm"
                                placeholder="{{ __('Contoh: Asisten Pemasaran') }}">
                            <x-input-error :messages="$errors->get('name')" class="mt-1" />
                        </div>

                        <div>
                            <label for="avatar"
                                class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('Foto Profil / Avatar') }}</label>
                            <div class="flex items-center gap-4">
                                @if($agent->avatar_path)
                                    <img src="{{ Storage::url($agent->avatar_path) }}"
                                        class="size-12 rounded-xl object-cover border border-slate-200 dark:border-slate-800 shadow-sm">
                                @else
                                    <div
                                        class="size-12 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 border border-slate-200 dark:border-slate-700">
                                        <span class="material-symbols-outlined text-[28px]">smart_toy</span>
                                    </div>
                                @endif
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
                                    <option value="{{ $id }}" {{ old('openrouter_model_id', $agent->openrouter_model_id) === $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="temperature"
                                class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('Temperatur (Kreativitas)') }}</label>
                            <div class="px-2">
                                <input type="range" id="temperature" name="temperature" min="0" max="2" step="0.1"
                                    value="{{ old('temperature', $agent->temperature) }}"
                                    class="w-full h-2 bg-slate-200 dark:bg-slate-800 rounded-lg appearance-none cursor-pointer accent-blue-600"
                                    oninput="document.getElementById('temp-value').textContent = this.value">
                                <div
                                    class="flex justify-between text-[10px] font-bold text-slate-500 dark:text-slate-400 mt-2 uppercase tracking-tighter">
                                    <span>{{ __('Presisi (0)') }}</span>
                                    <span id="temp-value"
                                        class="text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-2 py-0.5 rounded text-xs">{{ old('temperature', $agent->temperature) }}</span>
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
                                placeholder="{{ __('Berikan penjelasan singkat tentang identitas agen ini yang akan tampil di sidebar chat...') }}">{{ old('description', $agent->description) }}</textarea>
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
                                placeholder="{{ __('Berikan instruksi detail kepada agen tentang peran, gaya bicara, dan batasannya...') }}">{{ old('system_prompt', $agent->system_prompt) }}</textarea>
                            <x-input-error :messages="$errors->get('system_prompt')" class="mt-1" />
                        </div>
                    </div>

                    <!-- Section: Kemampuan -->
                    <div class="pt-6 border-t border-slate-100 dark:border-slate-800">
                        <label
                            class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-4">{{ __('Fitur & Kemampuan') }}</label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            @php $capabilities = old('capabilities', is_array($agent->capabilities) ? $agent->capabilities : []); @endphp
                            <!-- Text -->
                            <label
                                class="relative flex items-center p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/30 cursor-pointer hover:border-blue-500/50 transition-all group">
                                <div class="flex h-5 items-center">
                                    <input type="checkbox" name="capabilities[]" value="text" {{ in_array('text', $capabilities) ? 'checked' : '' }}
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
                                    <input type="checkbox" name="capabilities[]" value="image" {{ in_array('image', $capabilities) ? 'checked' : '' }}
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
                                    <input type="checkbox" name="capabilities[]" value="pdf" {{ in_array('pdf', $capabilities) ? 'checked' : '' }}
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
                                        {{ old('can_generate_excel', $agent->can_generate_excel ?? false) ? 'checked' : '' }}
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
                                    class="material-symbols-outlined text-[24px] text-emerald-500">{{ $agent->can_generate_excel ? 'check_circle' : 'table_chart' }}</span>
                            </label>

                            <!-- File Analysis -->
                            <label
                                class="relative flex items-center p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/30 cursor-pointer hover:border-purple-500/50 transition-all group">
                                <div class="flex h-5 items-center">
                                    <input type="checkbox" name="can_analyze_files" value="1"
                                        {{ old('can_analyze_files', $agent->can_analyze_files ?? false) ? 'checked' : '' }}
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
                                    class="material-symbols-outlined text-[24px] text-purple-500">{{ $agent->can_analyze_files ? 'check_circle' : 'attach_file' }}</span>
                            </label>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-3">
                            <span class="font-semibold text-purple-600 dark:text-purple-400">Analisis File:</span>
                            {{ __('Mengizinkan pengguna mengupload dokumen (PDF, Word, Excel, TXT, Gambar) untuk dianalisis oleh agen ini. Menggunakan model AI yang sesuai untuk ekstraksi dan analisis konten.') }}
                        </p>
                    </div>

                    <!-- Excel Generation & Quick Questions Section -->
                    <div class="space-y-6">
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
                            <h4 class="text-base font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">table_chart</span>
                                {{ __('Excel Generation') }}
                            </h4>
                            
                            <!-- Can Generate Excel -->
                            <div class="mb-4">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" 
                                        name="can_generate_excel" 
                                        value="1" 
                                        {{ old('can_generate_excel', $agent->can_generate_excel ?? false) ? 'checked' : '' }}
                                        class="w-5 h-5 rounded border-slate-300 dark:border-slate-600 text-emerald-600 focus:ring-emerald-500 bg-white dark:bg-slate-950">
                                    <div>
                                        <span class="block text-sm font-bold text-slate-900 dark:text-white">
                                            {{ __('Enable Excel Generation') }}
                                        </span>
                                        <span class="block text-xs text-slate-500 dark:text-slate-400">
                                            {{ __('Agent dapat membuat file Excel otomatis berdasarkan conversation') }}
                                        </span>
                                    </div>
                                </label>
                            </div>

                            <!-- Quick Questions -->
                            <div class="mb-4">
                                <label for="quick_questions" 
                                    class="block text-sm font-bold text-slate-900 dark:text-white mb-2">
                                    {{ __('Quick Questions (Clickable Buttons)') }}
                                </label>
                                <textarea
                                    name="quick_questions"
                                    id="quick_questions"
                                    rows="5"
                                    placeholder="{{ __('Enter one question per line') }}&#10;{{ __('Contoh:') }}&#10;{{ __('Hitung Profit First saya') }}&#10;{{ __('Buat Excel lengkap') }}&#10;{{ __('Analisis OPEX') }}"
                                    class="w-full px-4 py-3 bg-white dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition-all resize-none font-mono text-sm">{{ old('quick_questions', is_array($agent->quick_questions) ? implode("\n", $agent->quick_questions) : '') }}</textarea>
                                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                                    {{ __('Satu pertanyaan per baris. Tombol ini akan muncul di chatbox untuk diklik user.') }}
                                </p>
                            </div>

                            <!-- Greeting Message -->
                            <div>
                                <label for="greeting_message" 
                                    class="block text-sm font-bold text-slate-900 dark:text-white mb-2">
                                    {{ __('Greeting Message') }}
                                </label>
                                <textarea 
                                    name="greeting_message" 
                                    id="greeting_message" 
                                    rows="3"
                                    placeholder="{{ __('Pesan sambutan saat user memulai conversation baru...') }}"
                                    class="w-full px-4 py-3 bg-white dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition-all resize-none">{{ old('greeting_message', $agent->greeting_message ?? '') }}</textarea>
                                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                                    {{ __('Otomatis ditampilkan saat user memulai conversation baru') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Area -->
                    <div class="pt-8 flex items-center justify-end gap-4">
                        <a href="{{ route('admin.agents.index') }}"
                            class="px-6 py-2.5 text-sm font-bold text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-white transition-all">
                            {{ __('Batal') }}
                        </a>
                        <button type="submit"
                            class="px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-600/20 transition-all">
                            {{ __('Simpan Perubahan') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
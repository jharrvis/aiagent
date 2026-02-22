<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">
                    {{ __('Edit Pengguna') }}
                </h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1">
                    {{ __('Update informasi pengguna.') }}
                </p>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm p-8">
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                            {{ __('Nama Lengkap') }}
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition-all @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                            {{ __('Alamat Email') }}
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition-all @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                            {{ __('Kata Sandi Baru') }}
                        </label>
                        <input type="password" name="password" id="password"
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition-all @error('password') border-red-500 @enderror">
                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-500">
                            {{ __('Kosongkan jika tidak ingin mengubah kata sandi.') }}
                        </p>
                        @error('password')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <label for="password_confirmation"
                            class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                            {{ __('Konfirmasi Kata Sandi') }}
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition-all">
                    </div>

                    <!-- Role -->
                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">
                            {{ __('Role Pengguna') }}
                        </label>
                        <div class="flex items-center gap-3">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_admin" value="1"
                                    {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}
                                    class="rounded border-slate-300 dark:border-slate-600 text-emerald-600 focus:ring-emerald-500 bg-slate-50 dark:bg-slate-800">
                                <span class="text-sm text-slate-600 dark:text-slate-400">
                                    {{ __('Berikan akses Admin') }}
                                </span>
                            </label>
                        </div>
                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-500">
                            {{ __('Admin memiliki akses penuh ke panel admin untuk mengelola agen dan pengguna.') }}
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-3 pt-6 border-t border-slate-200 dark:border-slate-700">
                        <a href="{{ route('admin.users.index') }}"
                            class="px-6 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl font-semibold transition-all">
                            {{ __('Batal') }}
                        </a>
                        <button type="submit"
                            class="px-6 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-semibold transition-all shadow-lg shadow-emerald-500/20">
                            {{ __('Update Pengguna') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

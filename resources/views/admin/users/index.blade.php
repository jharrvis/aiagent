<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">
                        {{ __('Kelola Pengguna') }}
                    </h1>
                    <p class="text-slate-500 dark:text-slate-400 mt-1">
                        {{ __('Lihat dan kelola semua pengguna yang terdaftar.') }}
                    </p>
                </div>
                <a href="{{ route('admin.users.create') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-semibold transition-all shadow-lg shadow-emerald-500/20">
                    <span class="material-symbols-outlined text-[20px]">person_add</span>
                    {{ __('Tambah Pengguna') }}
                </a>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div
                    class="mb-6 p-4 bg-emerald-100 dark:bg-emerald-900/30 border-l-4 border-emerald-500 text-emerald-700 dark:text-emerald-400 rounded-r-xl shadow-sm flex items-center gap-3">
                    <span class="material-symbols-outlined">check_circle</span>
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div
                    class="mb-6 p-4 bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-400 rounded-r-xl shadow-sm flex items-center gap-3">
                    <span class="material-symbols-outlined">error</span>
                    <p class="font-medium">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Filters -->
            <div
                class="mb-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
                <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap gap-3">
                    <div class="flex-1 min-w-64">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="{{ __('Cari nama atau email...') }}"
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none">
                    </div>
                    <select name="role"
                        class="px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none cursor-pointer">
                        <option value="">{{ __('Semua Role') }}</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>{{ __('Admin') }}
                        </option>
                        <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>{{ __('User') }}</option>
                    </select>
                    <button type="submit"
                        class="px-5 py-2.5 bg-slate-900 dark:bg-white hover:bg-slate-800 dark:hover:bg-slate-100 text-white dark:text-slate-900 rounded-xl text-sm font-semibold transition-all">
                        {{ __('Filter') }}
                    </button>
                    @if(request('search') || request('role'))
                        <a href="{{ route('admin.users.index') }}"
                            class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl text-sm font-semibold transition-all">
                            {{ __('Reset') }}
                        </a>
                    @endif
                </form>
            </div>

            <!-- Users Table -->
            <div
                class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr
                                class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700">
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    {{ __('Pengguna') }}
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    {{ __('Role') }}
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    {{ __('Terdaftar') }}
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    {{ __('Saldo Token') }}
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    {{ __('Aktivitas') }}
                                </th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    {{ __('Aksi') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($users as $user)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($user->avatar)
                                                <img src="{{ $user->avatar }}" alt="{{ $user->name }}"
                                                    class="w-10 h-10 rounded-full object-cover border-2 border-slate-200 dark:border-slate-700">
                                            @else
                                                <div
                                                    class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-blue-500 flex items-center justify-center text-white font-bold text-sm">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <p class="font-semibold text-slate-900 dark:text-white">
                                                    {{ $user->name }}
                                                </p>
                                                <p class="text-sm text-slate-500 dark:text-slate-400">
                                                    {{ $user->email }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($user->is_admin)
                                            <span
                                                class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 border border-purple-200 dark:border-purple-700">
                                                <span class="material-symbols-outlined text-[14px]">admin_panel_settings</span>
                                                {{ __('Admin') }}
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                                <span class="material-symbols-outlined text-[14px]">person</span>
                                                {{ __('User') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">
                                        {{ $user->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            @if($user->is_admin)
                                                <span class="text-xs font-bold text-slate-400 flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-[16px]">all_inclusive</span>
                                                    ∞
                                                </span>
                                            @else
                                                <div class="flex flex-col">
                                                    <span
                                                        class="text-sm font-bold {{ $user->token_balance < 500 ? 'text-red-500' : 'text-slate-700 dark:text-slate-300' }}">
                                                        {{ number_format($user->token_balance, 0, ',', '.') }}
                                                    </span>
                                                    <button
                                                        @click="$dispatch('open-topup-modal', { id: {{ $user->id }}, name: '{{ addslashes($user->name) }}', balance: {{ $user->token_balance }} })"
                                                        class="text-[10px] font-bold text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 underline underline-offset-2 uppercase tracking-wider">
                                                        + Top-up
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">
                                        {{ $user->conversations()->count() }} {{ __('conversations') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.users.show', $user) }}"
                                                class="p-2 text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-all"
                                                title="{{ __('Lihat Detail') }}">
                                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                                class="p-2 text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-all"
                                                title="{{ __('Edit') }}">
                                                <span class="material-symbols-outlined text-[20px]">edit</span>
                                            </a>
                                            @if($user->id !== auth()->id())
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                                    class="inline"
                                                    onsubmit="return confirm('{{ __('Apakah Anda yakin ingin menghapus pengguna ini?') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="p-2 text-slate-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all"
                                                        title="{{ __('Hapus') }}">
                                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <span
                                                class="material-symbols-outlined text-5xl text-slate-300 dark:text-slate-600 mb-3">group_off</span>
                                            <p class="text-slate-500 dark:text-slate-400 font-medium">
                                                {{ __('Tidak ada pengguna ditemukan') }}
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($users->hasPages())
                    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Top-up Modal -->
    <div x-data="{ 
            isOpen: false, 
            userId: null, 
            userName: '', 
            currentBalance: 0,
            amount: 1000,
            isSubmitting: false,
            init() {
                window.addEventListener('open-topup-modal', (e) => {
                    this.userId = e.detail.id;
                    this.userName = e.detail.name;
                    this.currentBalance = e.detail.balance;
                    this.amount = 1000;
                    this.isOpen = true;
                });
            },
            async submitTopup() {
                if (this.amount <= 0) return;
                this.isSubmitting = true;
                
                try {
                    const response = await fetch(`/admin/users/${this.userId}/topup`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=&quot;csrf-token&quot;]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ amount: this.amount })
                    });

                    const data = await response.json();
                    
                    if (response.ok) {
                        window.location.reload();
                    } else {
                        alert(data.error || 'Terjadi kesalahan saat top-up.');
                    }
                } catch (error) {
                    alert('Terjadi kesalahan jaringan.');
                } finally {
                    this.isSubmitting = false;
                }
            }
        }" x-show="isOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">

        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Overlay -->
            <div x-show="isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="isOpen = false"
                class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal Panel -->
            <div x-show="isOpen" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-slate-800">

                <div
                    class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white" id="modal-title">
                        Top-up Token: <span x-text="userName" class="text-emerald-500"></span>
                    </h3>
                    <button @click="isOpen = false"
                        class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <div class="p-6">
                    <div
                        class="mb-4 p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-100 dark:border-slate-800">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Saldo Saat
                                Ini</span>
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-200"
                                x-text="new Intl.NumberFormat('id-ID').format(currentBalance)"></span>
                        </div>
                    </div>

                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                        Jumlah Token Tambahan
                    </label>
                    <div class="relative">
                        <span
                            class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-amber-500">diamond</span>
                        <input type="number" x-model="amount"
                            class="w-full pl-12 pr-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition-all"
                            placeholder="Masukkan jumlah token...">
                    </div>
                    <p class="mt-2 text-xs text-slate-500 italic">
                        * Total saldo baru: <span class="font-bold text-emerald-600 dark:text-emerald-400"
                            x-text="new Intl.NumberFormat('id-ID').format(parseInt(currentBalance) + parseInt(amount || 0))"></span>
                    </p>
                </div>

                <div
                    class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
                    <button @click="isOpen = false"
                        class="px-5 py-2 text-sm font-bold text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition-colors">
                        Batal
                    </button>
                    <button @click="submitTopup" :disabled="isSubmitting"
                        class="px-6 py-2.5 bg-emerald-500 hover:bg-emerald-600 disabled:opacity-50 text-white text-sm font-bold rounded-xl shadow-lg shadow-emerald-500/20 transition-all flex items-center gap-2">
                        <template x-if="isSubmitting">
                            <span
                                class="animate-spin h-4 w-4 border-2 border-white/30 border-t-white rounded-full"></span>
                        </template>
                        <span x-text="isSubmitting ? 'Memproses...' : 'Proses Top-up'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
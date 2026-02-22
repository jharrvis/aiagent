<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('admin.users.index') }}"
                    class="inline-flex items-center gap-2 text-slate-500 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors font-medium">
                    <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                    {{ __('Kembali ke Daftar Pengguna') }}
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- User Profile Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm p-6">
                        <div class="text-center">
                            @if($user->avatar)
                                <img src="{{ $user->avatar }}" alt="{{ $user->name }}"
                                    class="w-24 h-24 rounded-full object-cover border-4 border-emerald-100 dark:border-emerald-900/30 mx-auto mb-4">
                            @else
                                <div
                                    class="w-24 h-24 rounded-full bg-gradient-to-br from-emerald-400 to-blue-500 flex items-center justify-center text-white font-bold text-3xl mx-auto mb-4">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                            <h2 class="text-xl font-bold text-slate-900 dark:text-white">{{ $user->name }}</h2>
                            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">{{ $user->email }}</p>

                            <!-- Role Badge -->
                            <div class="mt-4">
                                @if($user->is_admin)
                                    <span
                                        class="inline-flex items-center gap-1 px-4 py-2 rounded-full text-xs font-bold bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 border border-purple-200 dark:border-purple-700">
                                        <span class="material-symbols-outlined text-[14px]">admin_panel_settings</span>
                                        {{ __('Admin') }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1 px-4 py-2 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                        <span class="material-symbols-outlined text-[14px]">person</span>
                                        {{ __('User') }}
                                    </span>
                                @endif
                            </div>

                            <!-- Account Info -->
                            <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-700">
                                <div class="space-y-3 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-slate-500 dark:text-slate-400">{{ __('Terdaftar') }}</span>
                                        <span class="font-semibold text-slate-900 dark:text-white">{{ $user->created_at->format('d M Y') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-500 dark:text-slate-400">{{ __('Terakhir Update') }}</span>
                                        <span class="font-semibold text-slate-900 dark:text-white">{{ $user->updated_at->diffForHumans() }}</span>
                                    </div>
                                    @if($user->email_verified_at)
                                        <div class="flex justify-between">
                                            <span class="text-slate-500 dark:text-slate-400">{{ __('Email Verified') }}</span>
                                            <span class="font-semibold text-emerald-600 dark:text-emerald-400">{{ $user->email_verified_at->format('d M Y') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="mt-6 flex flex-col gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}"
                                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-semibold transition-all">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                    {{ __('Edit Pengguna') }}
                                </a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST"
                                        onsubmit="return confirm('{{ __('Apakah Anda yakin ingin mengubah role pengguna ini?') }}')">
                                        @csrf
                                        <button type="submit"
                                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl font-semibold transition-all">
                                            <span class="material-symbols-outlined text-[18px]">swap_horiz</span>
                                            {{ __('Ubah ke ') }} {{ $user->is_admin ? __('User') : __('Admin') }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Statistics -->
                    <div class="mt-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm p-6">
                        <h3 class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-4">{{ __('Statistik') }}</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                        <span class="material-symbols-outlined text-[20px]">chat</span>
                                    </div>
                                    <span class="text-sm text-slate-600 dark:text-slate-400">{{ __('Konversasi') }}</span>
                                </div>
                                <span class="text-lg font-bold text-slate-900 dark:text-white">{{ $stats['total_conversations'] }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                                        <span class="material-symbols-outlined text-[20px]">message</span>
                                    </div>
                                    <span class="text-sm text-slate-600 dark:text-slate-400">{{ __('Pesan') }}</span>
                                </div>
                                <span class="text-lg font-bold text-slate-900 dark:text-white">{{ $stats['total_messages'] }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center text-purple-600 dark:text-purple-400">
                                    <span class="material-symbols-outlined text-[20px]">image</span>
                                </div>
                                <span class="text-sm text-slate-600 dark:text-slate-400">{{ __('Gambar') }}</span>
                                <span class="text-lg font-bold text-slate-900 dark:text-white">{{ $stats['total_images'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Conversations -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm p-6">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">{{ __('Riwayat Konversasi') }}</h3>

                        @if($user->conversations->count() > 0)
                            <div class="space-y-3">
                                @foreach($user->conversations as $conversation)
                                    <div
                                        class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-emerald-500/50 transition-all">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <h4 class="font-semibold text-slate-900 dark:text-white">
                                                        {{ $conversation->title }}
                                                    </h4>
                                                    @if($conversation->agent)
                                                        <span
                                                            class="px-2 py-0.5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded text-xs font-bold">
                                                            {{ $conversation->agent->name }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-sm text-slate-500 dark:text-slate-400">
                                                    <span class="material-symbols-outlined text-[14px] inline align-middle">calendar_today</span>
                                                    {{ $conversation->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                            <a href="{{ route('conversations.show', $conversation) }}"
                                                class="p-2 text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-all">
                                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if($user->conversations->count() >= 10)
                                <div class="mt-4 text-center">
                                    <a href="#"
                                        class="text-sm font-semibold text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300">
                                        {{ __('Lihat Semua Konversasi') }} →
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-12">
                                <span
                                    class="material-symbols-outlined text-5xl text-slate-300 dark:text-slate-600 mb-3">chat_off</span>
                                <p class="text-slate-500 dark:text-slate-400 font-medium">
                                    {{ __('Belum ada konversasi') }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

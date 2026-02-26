<x-app-layout>
    <!-- Scripts for Markdown & Syntax Highlighting -->
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-php.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-javascript.min.js"></script>
    {{-- =========================================================
    CHAT LAYOUT — Redesigned
    ========================================================= --}}
    <div class="flex-1 flex overflow-hidden h-[calc(100vh-65px)]" x-data="{
            sidebarOpen: JSON.parse(localStorage.getItem('sidebarOpen') ?? 'true'),
            quickOpen: false,
            toggleSidebar() {
                this.sidebarOpen = !this.sidebarOpen;
                localStorage.setItem('sidebarOpen', JSON.stringify(this.sidebarOpen));
            }
         }">

        {{-- ── SIDEBAR ──────────────────────────────────────────── --}}
        <aside :class="sidebarOpen ? 'w-64' : 'w-0'"
            class="hidden lg:flex flex-col flex-shrink-0 overflow-hidden transition-all duration-300 ease-in-out bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800">

            {{-- Agent Info Header --}}
            <div class="p-4 border-b border-slate-200 dark:border-slate-800 flex items-center gap-3 min-w-[16rem]">
                <div
                    class="h-10 w-10 rounded-xl bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-500/20 shrink-0">
                    @if($agent->avatar_path)
                        <img src="{{ Storage::url($agent->avatar_path) }}" class="w-full h-full rounded-xl object-cover"
                            alt="{{ $agent->name }}">
                    @else
                        <span class="material-symbols-outlined text-[22px]">smart_toy</span>
                    @endif
                </div>
                <div class="min-w-0">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ $agent->name }}</h3>
                    <div class="flex items-center gap-1 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                        <span class="text-xs text-slate-500">Aktif</span>
                    </div>
                </div>
            </div>

            {{-- Agent Body --}}
            <div class="p-4 flex-1 overflow-y-auto sidebar-scroll space-y-4 min-w-[16rem]"
                x-data="{ qOpen: true, hOpen: true }">

                <div>
                    <h4 class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2">
                        Tentang</h4>
                    <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                        {{ $agent->description ?: 'Agen AI yang siap membantu berbagai tugas Anda.' }}
                    </p>
                </div>

                @if(!empty($agent->capabilities))
                    <div>
                        <h4 class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2">
                            Kemampuan</h4>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($agent->capabilities as $cap)
                                <span
                                    class="px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-[10px] font-bold text-slate-600 dark:text-slate-300 uppercase">
                                    {{ ucfirst(str_replace('_', ' ', $cap)) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Collapsible Quick Questions --}}
                @if($agent->quick_questions && count($agent->quick_questions) > 0)
                    <div>
                        <button @click="qOpen = !qOpen"
                            class="flex items-center justify-between w-full text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                            <span>Pertanyaan Umum</span>
                            <span class="material-symbols-outlined text-[14px] transition-transform duration-200"
                                :class="qOpen ? 'rotate-180' : ''">expand_more</span>
                        </button>
                        <div x-show="qOpen" x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 -translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0" class="space-y-1">
                            @foreach($agent->quick_questions as $q)
                                <button type="button" onclick="sendQuickQuestion({{ json_encode($q) }})"
                                    class="w-full text-left text-xs text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 px-2 py-1.5 rounded-lg transition-colors leading-snug">
                                    {{ $q }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Conversation History --}}
                @if(isset($recentConversations) && $recentConversations->count() > 0)
                    <div>
                        <button @click="hOpen = !hOpen"
                            class="flex items-center justify-between w-full text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                            <span>Riwayat Chat</span>
                            <span class="material-symbols-outlined text-[14px] transition-transform duration-200"
                                :class="hOpen ? 'rotate-180' : ''">expand_more</span>
                        </button>
                        <div x-show="hOpen" x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 -translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0" class="space-y-0.5">
                            @foreach($recentConversations as $conv)
                                <a href="{{ route('conversations.show', $conv->id) }}"
                                    class="flex items-center gap-2 px-2 py-2 rounded-lg text-xs transition-colors group
                                                    {{ (isset($conversation) && $conversation->id == $conv->id) ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-800 dark:hover:text-slate-200' }}">
                                    <span
                                        class="material-symbols-outlined text-[14px] shrink-0 opacity-50">chat_bubble_outline</span>
                                    <span
                                        class="truncate flex-1">{{ $conv->title ?: 'Chat ' . $conv->created_at->format('d/m H:i') }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Back Button --}}
            <div class="p-3 border-t border-slate-200 dark:border-slate-800 min-w-[16rem]">
                <a href="{{ route('marketplace') }}"
                    class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors text-xs font-medium">
                    <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                    Kembali ke Marketplace
                </a>
            </div>
        </aside>

        {{-- ── MAIN CHAT ─────────────────────────────────────────── --}}
        <main class="flex-1 flex flex-col h-full bg-slate-50 dark:bg-slate-950 relative min-w-0"
            x-data="{ showAgentInfo: false }">

            {{-- ── HEADER ──────────────────────────────── --}}
            <header class="flex items-center justify-between px-3 py-2 bg-transparent z-10 shrink-0">

                {{-- Hamburger (desktop sidebar toggle) --}}
                <button @click="toggleSidebar()"
                    class="hidden lg:flex p-1.5 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-black/5 dark:hover:bg-white/5 transition-colors"
                    title="Toggle Sidebar">
                    <span class="material-symbols-outlined text-[22px]">menu</span>
                </button>

                {{-- Mobile: hamburger → opens agent info --}}
                <button
                    class="lg:hidden p-1.5 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-black/5 dark:hover:bg-white/5 transition-colors"
                    @click="showAgentInfo = !showAgentInfo">
                    <span class="material-symbols-outlined text-[22px]">menu</span>
                </button>

                {{-- 3-dots menu --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-black/5 dark:hover:bg-white/5 transition-colors"
                        title="Opsi">
                        <span class="material-symbols-outlined text-[22px]">more_horiz</span>
                    </button>
                    <div x-show="open" x-transition.origin.top.right
                        class="absolute right-0 top-10 w-52 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden z-[100] py-1"
                        style="display:none">
                        <a href="{{ route('agents.chat', $agent->id) }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                            <span class="material-symbols-outlined text-[18px] text-slate-400">add_comment</span>
                            Mulai Chat Baru
                        </a>
                        <button onclick="downloadChat()"
                            class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors text-left">
                            <span class="material-symbols-outlined text-[18px] text-slate-400">download</span>
                            Unduh Chat
                        </button>
                        @if(isset($conversation) && $conversation->id)
                            <div class="h-px bg-slate-200 dark:bg-slate-700 my-1"></div>
                            <button onclick="deleteChat({{ $conversation->id }})"
                                class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors text-left">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                Hapus Chat
                            </button>
                        @endif
                    </div>
                </div>
            </header>

            {{-- Mobile Agent Info Panel --}}
            <div x-show="showAgentInfo" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="lg:hidden bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 px-4 py-3 shrink-0"
                style="display:none">
                <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed mb-2">
                    {{ $agent->description ?: 'Agen AI yang siap membantu berbagai tugas Anda.' }}
                </p>
                @if(!empty($agent->capabilities))
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($agent->capabilities as $cap)
                            <span
                                class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-[10px] font-bold text-slate-500 uppercase">{{ ucfirst(str_replace('_', ' ', $cap)) }}</span>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ── MESSAGES AREA ───────────────────────── --}}
            <div id="messages" class="flex-1 overflow-y-auto py-6">

                @php $hasMessages = isset($conversation) && $conversation->messages->count() > 0; @endphp

                @if($hasMessages)
                    {{-- Existing Messages --}}
                    <div id="msg-inner" class="max-w-2xl mx-auto px-4 space-y-6">
                        @foreach($conversation->messages as $msg)
                            @php $isUser = $msg->role === 'user'; @endphp
                            @if($isUser)
                                {{-- User Message --}}
                                <div class="flex justify-end">
                                    <div class="group/user flex flex-col items-end gap-1 max-w-[85%]">
                                        <span class="text-xs text-slate-400 pr-1">{{ $msg->created_at->format('H:i') }}</span>
                                        <div
                                            class="bg-blue-600 text-white text-sm leading-relaxed px-4 py-3 rounded-2xl rounded-br-sm relative">
                                            <p class="whitespace-pre-wrap break-words">{{ $msg->content }}</p>
                                            <button data-content="{{ $msg->content }}"
                                                onclick="copyTextToClipboard(this, this.getAttribute('data-content'))"
                                                class="absolute -left-8 top-1/2 -translate-y-1/2 opacity-0 group-hover/user:opacity-100 p-1 text-slate-400 hover:text-blue-500 transition-opacity"
                                                title="Salin">
                                                <span class="material-symbols-outlined text-[16px]">content_copy</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @else
                                {{-- AI Message --}}
                                <div class="flex gap-3 group/bot">
                                    <div
                                        class="h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-500/20 shrink-0 mt-0.5">
                                        @if($agent->avatar_path)
                                            <img src="{{ Storage::url($agent->avatar_path) }}"
                                                class="w-full h-full rounded-full object-cover">
                                        @else
                                            <span class="material-symbols-outlined text-[16px]">smart_toy</span>
                                        @endif
                                    </div>
                                    <div class="flex flex-col gap-1 min-w-0 flex-1">
                                        <div class="flex items-baseline gap-2">
                                            <span
                                                class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $agent->name }}</span>
                                            <span class="text-xs text-slate-400">{{ $msg->created_at->format('H:i') }}</span>
                                        </div>
                                        <div class="text-slate-800 dark:text-slate-200 text-[15px] leading-relaxed">
                                            <div class="group/bot-inner flex items-start gap-2">
                                                <div class="flex-1 overflow-x-auto">
                                                    <div class="markdown-content"
                                                        data-raw-content="{{ base64_encode($msg->content) }}"></div>
                                                    @if(isset($msg->metadata['image_url']))
                                                        <div class="mt-3 group/img relative inline-block max-w-full">
                                                            <img src="{{ $msg->metadata['image_url'] }}"
                                                                class="max-w-full rounded-xl shadow-lg cursor-zoom-in block"
                                                                alt="Generated image" onclick="openImageModal(this.src)">
                                                            <div
                                                                class="absolute top-2 right-2 flex gap-1.5 opacity-0 group-hover/img:opacity-100 transition-opacity">
                                                                <button
                                                                    onclick="copyImageToClipboard('{{ $msg->metadata['image_url'] }}')"
                                                                    class="p-1.5 bg-black/60 hover:bg-black/80 text-white rounded-lg backdrop-blur-sm"
                                                                    title="Salin"><span
                                                                        class="material-symbols-outlined text-[16px]">content_copy</span></button>
                                                                <a href="{{ $msg->metadata['image_url'] }}" download="ai_image.png"
                                                                    class="p-1.5 bg-black/60 hover:bg-black/80 text-white rounded-lg backdrop-blur-sm"
                                                                    title="Unduh"><span
                                                                        class="material-symbols-outlined text-[16px]">download</span></a>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if(isset($msg->metadata['pdf_path']))
                                                        <div class="mt-3">
                                                            <a href="{{ route('messages.pdf', ['message' => $msg->id]) }}"
                                                                class="inline-flex items-center px-3 py-1.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 rounded-md text-xs font-medium text-blue-600 dark:text-blue-400 transition-colors gap-1">
                                                                <span
                                                                    class="material-symbols-outlined text-[16px]">picture_as_pdf</span>
                                                                Unduh Laporan PDF
                                                            </a>
                                                        </div>
                                                    @endif
                                                    @if(isset($msg->metadata['excel_path']))
                                                        <div class="mt-3">
                                                            <a href="{{ route('messages.excel', ['message' => $msg->id]) }}"
                                                                class="inline-flex items-center px-3 py-1.5 bg-emerald-100 dark:bg-emerald-900/30 hover:bg-emerald-200 dark:hover:bg-emerald-800 border border-emerald-200 dark:border-emerald-700 rounded-md text-xs font-medium text-emerald-700 dark:text-emerald-400 transition-colors gap-1">
                                                                <span class="material-symbols-outlined text-[16px]">table_view</span>
                                                                Unduh File Excel
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                                <button data-content="{{ $msg->content }}"
                                                    onclick="copyTextToClipboard(this, this.getAttribute('data-content'))"
                                                    class="opacity-0 group-hover/bot:opacity-100 p-1 text-slate-400 hover:text-blue-500 transition-opacity shrink-0 mt-1"
                                                    title="Salin">
                                                    <span class="material-symbols-outlined text-[16px]">content_copy</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    {{-- ── WELCOME SCREEN (ChatGPT-style) ── --}}
                    <div id="welcome-screen"
                        class="max-w-2xl mx-auto px-4 flex flex-col items-center text-center pt-8 pb-4">
                        {{-- Agent Avatar --}}
                        <div
                            class="h-16 w-16 rounded-2xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-500/30 mb-4 shadow-lg">
                            @if($agent->avatar_path)
                                <img src="{{ Storage::url($agent->avatar_path) }}"
                                    class="w-full h-full rounded-2xl object-cover">
                            @else
                                <span class="material-symbols-outlined text-[36px]">smart_toy</span>
                            @endif
                        </div>
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-1">{{ $agent->name }}</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400 max-w-md leading-relaxed mb-8">
                            {{ $agent->greeting_message ?? ('Halo! Saya ' . $agent->name . '. Ada yang bisa saya bantu hari ini?') }}
                        </p>

                        {{-- Quick Question Grid --}}
                        @if($agent->quick_questions && count($agent->quick_questions) > 0)
                            <div class="w-full grid grid-cols-1 sm:grid-cols-2 gap-3 text-left">
                                @foreach($agent->quick_questions as $question)
                                    <button type="button" onclick="sendQuickQuestion({{ json_encode($question) }})"
                                        class="group px-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 hover:border-blue-400 dark:hover:border-blue-500 hover:bg-blue-50/50 dark:hover:bg-blue-900/10 text-left text-sm text-slate-700 dark:text-slate-300 hover:text-blue-700 dark:hover:text-blue-300 transition-all shadow-sm hover:shadow-md leading-snug">
                                        <span
                                            class="material-symbols-outlined text-[16px] text-blue-500 mr-1.5 align-middle">spark</span>{{ $question }}
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            {{-- ── INPUT AREA ──────────────────────────── --}}
            <div class="shrink-0 bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800">

                {{-- Quick Questions Popover (accessible any time) --}}
                @if($agent->quick_questions && count($agent->quick_questions) > 0)
                    <div id="quick-questions-popover"
                        class="hidden absolute bottom-full left-0 right-0 z-20 mx-3 mb-1 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-2xl overflow-hidden">
                        <div
                            class="flex items-center justify-between px-4 py-2.5 border-b border-slate-200 dark:border-slate-700">
                            <span
                                class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px] text-blue-500">tips_and_updates</span>
                                Ide Pertanyaan
                            </span>
                            <button onclick="toggleQuickQuestions()"
                                class="p-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700">
                                <span class="material-symbols-outlined text-[18px]">close</span>
                            </button>
                        </div>
                        <div class="p-3 grid grid-cols-1 gap-1.5 max-h-52 overflow-y-auto">
                            @foreach($agent->quick_questions as $question)
                                <button type="button" onclick="sendQuickQuestion({{ json_encode($question) }})"
                                    class="text-left px-3 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-700 dark:hover:text-blue-300 rounded-lg transition-colors leading-snug">
                                    <span
                                        class="material-symbols-outlined text-[14px] text-blue-400 mr-1 align-middle">spark</span>{{ $question }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form id="chat-form" class="p-3 relative">
                    @csrf
                    <input type="hidden" name="conversation_id" id="conversation_id" value="">

                    {{-- Input Box --}}
                    <div
                        class="flex items-end gap-2 bg-slate-50 dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-transparent transition-all px-3 py-2">

                        {{-- Left Toolbar --}}
                        <div class="flex items-center gap-1 shrink-0 pb-0.5">
                            {{-- Ide / Quick Questions toggle --}}
                            @if($agent->quick_questions && count($agent->quick_questions) > 0)
                                <button type="button" onclick="toggleQuickQuestions()"
                                    class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-slate-700 transition-colors"
                                    title="Ide Pertanyaan">
                                    <span class="material-symbols-outlined text-[20px]">tips_and_updates</span>
                                </button>
                            @endif

                            {{-- Image Mode toggle --}}
                            @if($agent->hasCapability('image'))
                                <label
                                    class="flex items-center cursor-pointer p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                                    title="Mode Gambar">
                                    <input type="checkbox" id="image-mode-toggle" class="sr-only">
                                    <span class="material-symbols-outlined text-[20px] text-slate-400"
                                        id="image-mode-icon">brush</span>
                                </label>
                                <div id="image-options"
                                    class="hidden items-center gap-1.5 border-l border-slate-200 dark:border-slate-700 pl-2">
                                    <select id="image-size"
                                        class="text-xs bg-transparent border-none text-slate-500 dark:text-slate-400 py-0 pr-5 pl-0 focus:ring-0 outline-none font-medium cursor-pointer">
                                        <option value="1:1">1:1</option>
                                        <option value="16:9">16:9</option>
                                        <option value="9:16">9:16</option>
                                    </select>
                                </div>
                            @endif
                        </div>

                        {{-- Textarea --}}
                        <textarea id="message-input" name="content"
                            class="flex-1 bg-transparent border-none text-slate-900 dark:text-white placeholder-slate-400 focus:ring-0 resize-none py-1.5 text-sm max-h-36 leading-relaxed"
                            placeholder="Tanya {{ $agent->name }}..." rows="1" required></textarea>

                        {{-- Send / Stop button --}}
                        <div class="shrink-0 pb-0.5">
                            <button type="submit" id="send-btn"
                                class="bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-all flex items-center justify-center w-9 h-9 relative overflow-hidden"
                                title="Kirim">
                                <span
                                    class="material-symbols-outlined text-[18px] transition-all duration-200 absolute inset-0 flex items-center justify-center"
                                    id="send-icon">send</span>
                                <span
                                    class="material-symbols-outlined text-[18px] transition-all duration-200 scale-0 opacity-0 absolute inset-0 flex items-center justify-center"
                                    id="stop-icon">stop</span>
                            </button>
                        </div>
                    </div>

                    {{-- Image options expanded bar --}}
                    @if($agent->hasCapability('image'))
                        <div id="image-size-bar" class="hidden mt-2 px-1 flex items-center gap-2">
                            <span class="text-xs text-slate-500">Rasio:</span>
                        </div>
                    @endif
                </form>

                <p class="text-center text-[10px] text-slate-400 pb-2 px-4">
                    AI dapat membuat kesalahan. Pertimbangkan untuk memeriksa informasi penting.
                </p>
            </div>
        </main>
    </div>


    <script>
        function toggleQuickQuestions() {
            const qqPopover = document.getElementById('quick-questions-popover');
            if (qqPopover) {
                qqPopover.classList.toggle('hidden');
                // Optional: scroll slightly to reveal them if input is at the bottom
                setTimeout(() => {
                    const messagesEl = document.getElementById('messages');
                    messagesEl.scrollTop = messagesEl.scrollHeight;
                }, 50);
            }
        }

        function sendQuickQuestion(question) {
            const messageInput = document.getElementById('message-input');
            messageInput.value = question;
            messageInput.style.height = 'auto';
            messageInput.style.height = messageInput.scrollHeight + 'px';

            // Hide the popover and welcome screen
            const qqPopover = document.getElementById('quick-questions-popover');
            if (qqPopover) qqPopover.classList.add('hidden');

            document.getElementById('chat-form').dispatchEvent(new Event('submit', { cancelable: true }));
        }
    </script>

    <style>
        .markdown-content h1,
        .markdown-content h2,
        .markdown-content h3 {
            font-weight: 700;
            color: inherit;
            margin: 1.5rem 0 1rem 0;
        }

        .markdown-content h1 {
            font-size: 1.5rem;
        }

        .markdown-content h2 {
            font-size: 1.25rem;
        }

        .markdown-content h3 {
            font-size: 1.125rem;
        }

        .markdown-content ul,
        .markdown-content ol {
            padding-left: 1.5rem;
            margin: 1rem 0;
        }

        .markdown-content ul {
            list-style-type: disc;
        }

        .markdown-content ol {
            list-style-type: decimal;
        }

        .markdown-content code {
            background: rgba(148, 163, 184, 0.2);
            padding: 0.2rem 0.4rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-family: 'Fira Code', monospace;
        }

        .markdown-content pre {
            background: #1e293b !important;
            padding: 1rem !important;
            border-radius: 0.75rem !important;
            overflow-x: auto;
            margin: 1.5rem 0 !important;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .markdown-content pre code {
            background: transparent !important;
            padding: 0 !important;
            color: #f8fafc !important;
            font-size: 0.8125rem !important;
            text-shadow: none !important;
        }

        .markdown-content p {
            margin-bottom: 1rem;
            line-height: 1.6;
        }

        .markdown-content p:last-child {
            margin-bottom: 0;
        }

        .markdown-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
            font-size: 0.875rem;
        }

        .markdown-content th,
        .markdown-content td {
            border: 1px solid rgba(148, 163, 184, 0.2);
            padding: 0.75rem;
            text-align: left;
        }

        .markdown-content th {
            background: rgba(148, 163, 184, 0.1);
            font-weight: 600;
        }

        .markdown-content blockquote {
            border-left: 4px solid #3b82f6;
            padding-left: 1rem;
            font-style: italic;
            color: #64748b;
            margin: 1rem 0;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Modern Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.3);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(148, 163, 184, 0.5);
        }

        .dark ::-webkit-scrollbar-thumb {
            background: rgba(71, 85, 105, 0.5);
        }

        .dark ::-webkit-scrollbar-thumb:hover {
            background: rgba(71, 85, 105, 0.8);
        }
    </style>

    <script>
        const agentId = {{ $agent->id }};
        const conversationIdEl = document.getElementById('conversation_id');
        const messagesEl = document.getElementById('messages');
        const chatForm = document.getElementById('chat-form');
        const messageInput = document.getElementById('message-input');
        const sendBtn = document.getElementById('send-btn');

        let conversationId = {{ isset($conversation) ? $conversation->id : 'null' }};
        if (conversationId) {
            conversationIdEl.value = conversationId;
            messagesEl.scrollTop = messagesEl.scrollHeight;
        }

        // Auto-resize textarea
        messageInput.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        async function createConversation() {
            const response = await fetch('{{ route("conversations.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ agent_id: agentId })
            });
            const data = await response.json();
            conversationId = data.id || conversationId;
            conversationIdEl.value = conversationId;
        }

        function hideWelcomeScreen() {
            const ws = document.getElementById('welcome-screen');
            if (ws) ws.remove();
            // Ensure the inner container exists
            if (!document.getElementById('msg-inner')) {
                const inner = document.createElement('div');
                inner.id = 'msg-inner';
                inner.className = 'max-w-2xl mx-auto px-4 space-y-6';
                messagesEl.appendChild(inner);
            }
        }

        function getMessageContainer() {
            // Return the existing inner container or messagesEl as fallback
            return document.getElementById('msg-inner') || messagesEl;
        }

        function addMessage(role, content, metadata = {}) {
            hideWelcomeScreen();
            const container = getMessageContainer();
            const messageDiv = document.createElement('div');
            const isUser = role === 'user';

            messageDiv.className = isUser ? 'flex justify-end' : 'flex gap-3 group/bot';

            if (isUser) {
                messageDiv.innerHTML = `
                    <div class="group/user flex flex-col items-end gap-1 max-w-[85%]">
                        <span class="text-xs text-slate-400 pr-1">${new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</span>
                        <div class="bg-blue-600 text-white text-sm leading-relaxed px-4 py-3 rounded-2xl rounded-br-sm relative">
                            <p class="whitespace-pre-wrap break-words">${escapeHtml(content)}</p>
                            <button data-content="${escapeHtml(content)}" onclick="copyTextToClipboard(this, this.getAttribute('data-content'))"
                                class="absolute -left-8 top-1/2 -translate-y-1/2 opacity-0 group-hover/user:opacity-100 p-1 text-slate-400 hover:text-blue-500 transition-opacity" title="Salin">
                                <span class="material-symbols-outlined text-[16px]">content_copy</span>
                            </button>
                        </div>
                    </div>
                `;
            } else {
                let processedContent = parseReasoningTags(content);
                let contentHtml = `<div class="markdown-content text-[15px]">${renderMarkdown(processedContent)}</div>`;

                if (metadata.image_url) {
                    contentHtml += `
                        <div class="mt-3 group/img relative inline-block max-w-full">
                            <img src="${metadata.image_url}" class="max-w-full rounded-xl shadow-lg cursor-zoom-in block" alt="Generated image" onclick="openImageModal(this.src)">
                            <div class="absolute top-2 right-2 flex gap-1.5 opacity-0 group-hover/img:opacity-100 transition-opacity">
                                <button onclick="copyImageToClipboard('${metadata.image_url}')" class="p-1.5 bg-black/60 hover:bg-black/80 text-white rounded-lg backdrop-blur-sm" title="Copy Gambar">
                                    <span class="material-symbols-outlined text-[16px]">content_copy</span>
                                </button>
                                <a href="${metadata.image_url}" download="ai_image.png" class="p-1.5 bg-black/60 hover:bg-black/80 text-white rounded-lg backdrop-blur-sm" title="Download Gambar">
                                    <span class="material-symbols-outlined text-[16px]">download</span>
                                </a>
                            </div>
                        </div>
                    `;
                }

                if (metadata.pdf_path) {
                    contentHtml += `<div class="mt-3"><a href="/messages/${metadata.message_id}/pdf" class="inline-flex items-center px-3 py-1.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 rounded-md text-xs font-medium text-blue-600 dark:text-blue-400 transition-colors gap-1"><span class="material-symbols-outlined text-[16px]">picture_as_pdf</span>{{ __('Unduh Laporan PDF') }}</a></div>`;
                }

                if (metadata.excel_path) {
                    contentHtml += `<div class="mt-3"><a href="/messages/${metadata.message_id}/excel" class="inline-flex items-center px-3 py-1.5 bg-emerald-100 dark:bg-emerald-900/30 hover:bg-emerald-200 dark:hover:bg-emerald-800 border border-emerald-200 dark:border-emerald-700 rounded-md text-xs font-medium text-emerald-700 dark:text-emerald-400 transition-colors gap-1"><span class="material-symbols-outlined text-[16px]">table_view</span>{{ __('Unduh File Excel') }}</a></div>`;
                }

                messageDiv.innerHTML = `
                    <div class="h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center shrink-0 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-500/20 mt-0.5">
                        <span class="material-symbols-outlined text-[16px]">smart_toy</span>
                    </div>
                    <div class="flex flex-col gap-1 min-w-0 flex-1">
                        <div class="flex items-baseline gap-2">
                            <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">${'{{ $agent->name }}'}</span>
                            <span class="text-xs text-slate-400">${new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</span>
                        </div>
                        <div class="text-slate-800 dark:text-slate-200 leading-relaxed">
                            <div class="group/bot-inner flex items-start gap-2">
                                <div class="flex-1 overflow-x-auto">${contentHtml}</div>
                                <button data-content="${escapeHtml(content)}" onclick="copyTextToClipboard(this, this.getAttribute('data-content'))"
                                    class="opacity-0 group-hover/bot:opacity-100 p-1 text-slate-400 hover:text-blue-500 transition-opacity shrink-0 mt-1" title="Salin">
                                    <span class="material-symbols-outlined text-[16px]">content_copy</span>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            }

            container.appendChild(messageDiv);
            messagesEl.scrollTop = messagesEl.scrollHeight;

            if (!isUser) {
                Prism.highlightAllUnder(messageDiv);
            }
        }

        function addTypingIndicator() {
            const indicator = document.createElement('div');
            indicator.id = 'typing-indicator';
            indicator.className = 'flex gap-3 mt-2';
            indicator.innerHTML = `
                <div class="h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center shrink-0 text-blue-500 border border-blue-200 dark:border-blue-500/20 mt-0.5">
                    <span class="material-symbols-outlined text-[16px]">smart_toy</span>
                </div>
                <div class="flex items-center">
                    <div class="px-4 py-3 rounded-2xl rounded-tl-sm flex gap-1.5 items-center bg-slate-100 dark:bg-slate-800">
                        <div class="w-1.5 h-1.5 bg-slate-500 rounded-full animate-bounce" style="animation-delay: 0s"></div>
                        <div class="w-1.5 h-1.5 bg-slate-500 rounded-full animate-bounce" style="animation-delay: 0.15s"></div>
                        <div class="w-1.5 h-1.5 bg-slate-500 rounded-full animate-bounce" style="animation-delay: 0.3s"></div>
                    </div>
                </div>
            `;
            const container = getMessageContainer();
            container.appendChild(indicator);
            messagesEl.scrollTop = messagesEl.scrollHeight;
        }

        function removeTypingIndicator() {
            const indicator = document.getElementById('typing-indicator');
            if (indicator) indicator.remove();
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function renderMarkdown(text) {
            marked.setOptions({
                breaks: true,
                gfm: true
            });
            return marked.parse(text);
        }

        let currentAbortController = null;

        function setGeneratingState(isGenerating) {
            const sendIcon = document.getElementById('send-icon');
            const stopIcon = document.getElementById('stop-icon');
            const sendBtn = document.getElementById('send-btn');

            if (isGenerating) {
                sendBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                sendBtn.classList.add('bg-red-500', 'hover:bg-red-600', 'animate-pulse');
                sendIcon.classList.replace('scale-100', 'scale-0');
                sendIcon.classList.add('opacity-0');
                stopIcon.classList.replace('scale-0', 'scale-100');
                stopIcon.classList.remove('opacity-0');
            } else {
                sendBtn.classList.remove('bg-red-500', 'hover:bg-red-600', 'animate-pulse');
                sendBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');
                sendIcon.classList.replace('scale-0', 'scale-100');
                sendIcon.classList.remove('opacity-0');
                stopIcon.classList.replace('scale-100', 'scale-0');
                stopIcon.classList.add('opacity-0');
            }
        }

        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            // If we are currently generating, act as a Stop button
            if (currentAbortController) {
                currentAbortController.abort();
                currentAbortController = null;
                removeTypingIndicator();
                setGeneratingState(false);
                addMessage('assistant', '_{{ __("Respon dibatalkan oleh pengguna.") }}_');
                messageInput.disabled = false;
                messageInput.focus();
                return;
            }

            const content = messageInput.value.trim();
            if (!content) return;

            // ── Instant UI feedback (no blocking) ──
            addMessage('user', content);
            messageInput.value = '';
            messageInput.style.height = 'auto';
            messageInput.disabled = true;

            // Hide quick questions popover and welcome screen
            const qqPopover = document.getElementById('quick-questions-popover');
            if (qqPopover) qqPopover.classList.add('hidden');

            // Show typing + generating state immediately, BEFORE awaiting network
            addTypingIndicator();
            setGeneratingState(true);

            if (!conversationId) {
                try {
                    await createConversation();
                } catch (error) {
                    removeTypingIndicator();
                    setGeneratingState(false);
                    addMessage('assistant', '{{ __("Gagal membuat percakapan. Silakan segarkan halaman.") }}');
                    messageInput.disabled = false;
                    return;
                }
            }

            let requestBody = {
                conversation_id: conversationId,
                content: content
            };

            const imageToggle = document.getElementById('image-mode-toggle');
            if (imageToggle && imageToggle.checked) {
                const imageSize = document.getElementById('image-size').value;
                requestBody.is_image_request = true;
                requestBody.image_size = imageSize;
            }

            currentAbortController = new AbortController();

            try {
                const response = await fetch('{{ route("messages.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(requestBody),
                    signal: currentAbortController.signal
                });

                removeTypingIndicator();

                if (response.ok) {
                    const data = await response.json();
                    addMessage('assistant', data.assistant_message.content, {
                        ...data.assistant_message.metadata,
                        message_id: data.assistant_message.id
                    });
                } else {
                    const data = await response.json();
                    const errorMessage = data.error || '{{ __("Maaf, terjadi kesalahan saat memproses permintaan Anda. Silakan coba lagi nanti.") }}';
                    addMessage('assistant', errorMessage);
                }
            } catch (error) {
                removeTypingIndicator();
                if (error.name === 'AbortError') {
                    // Handled synchronously by the abort button click
                    console.log("Fetch aborted");
                } else {
                    addMessage('assistant', '{{ __("Maaf, terjadi kesalahan jaringan. Periksa koneksi internet Anda.") }}');
                }
            } finally {
                currentAbortController = null;
                setGeneratingState(false);
                messageInput.disabled = false;
                messageInput.focus();
            }
        });

        // Allow Shift+Enter for new line, Enter for submit
        messageInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                chatForm.dispatchEvent(new Event('submit'));
            }
        });

        // Delete chat functionality
        async function deleteChat(convId) {
            if (!confirm('{{ __("Apakah Anda yakin ingin menghapus percakapan ini secara permanen?") }}')) {
                return;
            }

            try {
                const response = await fetch(`/conversations/${convId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                if (response.ok || response.redirected) {
                    window.location.href = '{{ route("agents.chat", $agent->id) }}';
                } else {
                    alert('{{ __("Gagal menghapus percakapan.") }}');
                }
            } catch (error) {
                alert('{{ __("Terjadi kesalahan jaringan saat menghapus percakapan.") }}');
            }
        }

        // Download chat functionality
        function downloadChat() {
            if (!conversationId) {
                alert('{{ __("Belum ada percakapan untuk diunduh.") }}');
                return;
            }

            let chatText = `# Percakapan dengan {{ $agent->name }}\nTanggal: ${new Date().toLocaleDateString('id-ID')}\n\n`;

            // Extract messages from DOM
            const messageNodes = messagesEl.children;
            let hasMessages = false;

            // Skip the first message if it's the welcome message (doesn't have a copy button)
            for (let i = 0; i < messageNodes.length; i++) {
                const node = messageNodes[i];
                if (node.id === 'typing-indicator') continue;

                const copyBtn = node.querySelector('[data-content]');
                if (!copyBtn) continue; // Skip welcome message

                hasMessages = true;
                const isUser = node.querySelector('.bg-blue-600') !== null; // Check if user bubble
                const role = isUser ? 'Anda' : '{{ $agent->name }}';
                const content = copyBtn.getAttribute('data-content');
                const timeSpan = node.querySelector('span.text-xs.text-slate-500');
                const time = timeSpan ? timeSpan.innerText : '';

                chatText += `### ${role} ${time ? '(' + time + ')' : ''}\n${content}\n\n---\n\n`;
            }

            if (!hasMessages) {
                alert('{{ __("Belum ada riwayat obrolan untuk diunduh.") }}');
                return;
            }

            // Create blob and download
            const blob = new Blob([chatText], { type: 'text/markdown;charset=utf-8' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `Chat_{{ $agent->name }}_${new Date().toISOString().split('T')[0]}.md`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }

        // Parse <think> tags for UI
        function parseReasoningTags(text) {
            return text.replace(/<think>([\s\S]*?)<\/think>/g, function (match, inner) {
                return `
                <details class="mb-4 text-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden group/details">
                    <summary class="cursor-pointer bg-slate-50 dark:bg-slate-800/50 px-3 py-2 font-medium text-slate-600 dark:text-slate-300 flex items-center gap-2 hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-colors">
                        <span class="material-symbols-outlined text-[18px] text-blue-500">psychology</span>
                        <span class="reasoning-text font-bold">Proses Berpikir AI</span>
                        <span class="material-symbols-outlined text-[18px] ml-auto transition-transform group-open/details:rotate-180">expand_more</span>
                    </summary>
                    <div class="p-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 italic text-xs leading-relaxed opacity-90 pb-2">
                        ${inner.trim()}
                    </div>
                </details>
                `;
            });
        }

        // Format existing markdown messages on load
        document.querySelectorAll('.markdown-content').forEach(el => {
            let rawContent = '';
            if (el.hasAttribute('data-raw-content')) {
                // Decode base64 using atob, handling utf-8 correctly
                rawContent = decodeURIComponent(escape(window.atob(el.getAttribute('data-raw-content'))));
            } else {
                rawContent = el.textContent;
            }

            rawContent = parseReasoningTags(rawContent);
            el.innerHTML = renderMarkdown(rawContent);
            Prism.highlightAllUnder(el);
        });

        messageInput.focus();
    </script>

    <!-- Global Image Viewer Modal -->
    <div id="imageModal"
        class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4"
        onclick="if(event.target===this)closeImageModal()">
        <div class="relative max-w-5xl w-full flex flex-col items-center gap-4">
            <button onclick="closeImageModal()"
                class="absolute -top-3 -right-3 z-10 p-2 bg-white/10 hover:bg-white/20 rounded-full text-white backdrop-blur-md transition-all border border-white/20">
                <span class="material-symbols-outlined">close</span>
            </button>
            <img id="modalImage" src="" alt="AI Generated Image"
                class="max-w-full max-h-[80vh] rounded-2xl object-contain shadow-2xl border border-white/10">
            <div class="flex items-center gap-3">
                <button id="copyModalBtn" onclick="copyImageToClipboard(document.getElementById('modalImage').src)"
                    class="flex items-center gap-2 px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-xl font-semibold backdrop-blur-md transition-all border border-white/20">
                    <span class="material-symbols-outlined text-[18px]">content_copy</span>
                    Copy Gambar
                </button>
                <a id="downloadModalBtn" href="" download="ai_image.png"
                    class="flex items-center gap-2 px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-semibold shadow-lg shadow-emerald-500/20 transition-all">
                    <span class="material-symbols-outlined text-[18px]">download</span>
                    Download
                </a>
            </div>
        </div>
    </div>

    <script>
        function openImageModal(src) {
            const modal = document.getElementById('imageModal');
            document.getElementById('modalImage').src = src;
            document.getElementById('downloadModalBtn').href = src;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }
        async function copyImageToClipboard(src) {
            try {
                const response = await fetch(src);
                const blob = await response.blob();
                await navigator.clipboard.write([new ClipboardItem({ [blob.type]: blob })]);
                const btn = document.getElementById('copyModalBtn');
                if (btn) {
                    const orig = btn.innerHTML;
                    btn.innerHTML = '<span class="material-symbols-outlined text-[18px]">check</span> Tersalin!';
                    setTimeout(() => { btn.innerHTML = orig; }, 2000);
                }
            } catch (e) {
                navigator.clipboard.writeText(src).catch(() => { });
            }
        }
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeImageModal(); });
        function copyTextToClipboard(btn, text) {
            // Use Clipboard API if available (HTTPS), otherwise fall back to execCommand (HTTP/local)
            const doCopy = () => {
                if (navigator.clipboard && window.isSecureContext) {
                    return navigator.clipboard.writeText(text);
                }
                // Fallback for non-secure contexts (HTTP)
                const textarea = document.createElement('textarea');
                textarea.value = text;
                textarea.style.position = 'fixed';
                textarea.style.opacity = '0';
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                return Promise.resolve();
            };

            doCopy().then(() => {
                const icon = btn.querySelector('span');
                if (!icon) return;
                const origText = icon.innerText;
                icon.innerText = 'check';
                btn.classList.add('!text-green-500');
                setTimeout(() => {
                    icon.innerText = origText;
                    btn.classList.remove('!text-green-500');
                }, 2000);
            }).catch(e => console.error('Copy failed:', e));
        }
    </script>
</x-app-layout>
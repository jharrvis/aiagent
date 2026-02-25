<x-app-layout>
    <!-- Scripts for Markdown & Syntax Highlighting -->
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-php.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-javascript.min.js"></script>
    <div class="flex-1 flex overflow-hidden h-[calc(100vh-65px)]">
        <!-- Sidebar Kiri: Info Agen -->
        <aside
            class="w-72 hidden lg:flex flex-col bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 shrink-0">
            <div class="p-6 border-b border-slate-800 flex flex-col items-center text-center">
                <div
                    class="h-20 w-20 rounded-full bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-500/20 mb-4 shadow-xl">
                    @if($agent->avatar_path)
                        <img src="{{ Storage::disk('public')->url($agent->avatar_path) }}"
                            class="w-full h-full rounded-full object-cover">
                    @else
                        <span class="material-symbols-outlined text-[40px]">smart_toy</span>
                    @endif
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">{{ $agent->name }}</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $agent->openrouter_model_id }}</p>
            </div>
            <div class="p-6 flex-1 overflow-y-auto">
                <div class="mb-6">
                    <h4 class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-3">
                        {{ __('Tentang') }}
                    </h4>
                    <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                        {{ $agent->description ?: __('Agen AI yang siap membantu berbagai tugas Anda dengan cerdik dan cepat.') }}
                    </p>
                </div>
                <div class="mb-6">
                    <h4 class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-3">
                        {{ __('Kemampuan') }}
                    </h4>
                    <div class="flex flex-wrap gap-2">
                        @forelse($agent->capabilities ?? ['text'] as $cap)
                            <span
                                class="px-2.5 py-1 rounded-md bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-[10px] font-bold text-slate-600 dark:text-slate-300 uppercase">
                                {{ ucfirst(str_replace('_', ' ', $cap)) }}
                            </span>
                        @empty
                            <span class="text-xs text-slate-500 italic">{{ __('Tidak ada kemampuan khusus') }}</span>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="p-4 border-t border-slate-800">
                <a href="{{ route('marketplace') }}"
                    class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-lg border border-slate-700 text-slate-300 hover:text-white hover:bg-slate-800 transition-colors text-sm font-medium">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    {{ __('Kembali ke Marketplace') }}
                </a>
            </div>
        </aside>

        <!-- Area Chat Utama -->
        <main class="flex-1 flex flex-col h-full bg-slate-50 dark:bg-slate-950 relative">
            <!-- Header Chat -->
            <header
                class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 z-10">
                <div class="flex items-center gap-3">
                    <div
                        class="md:hidden h-8 w-8 rounded-full bg-blue-900/20 flex items-center justify-center text-blue-400 border border-blue-500/20">
                        <span class="material-symbols-outlined text-[18px]">smart_toy</span>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900 dark:text-white leading-tight">{{ $agent->name }}
                        </h2>
                        <div class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                            <span
                                class="text-xs text-slate-500 dark:text-slate-400 font-medium">{{ __('Aktif Sekarang') }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button
                        class="p-2 text-slate-400 hover:text-blue-600 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors"
                        title="{{ __('Unduh Chat') }}">
                        <span class="material-symbols-outlined text-[20px]">download</span>
                    </button>
                    <button
                        class="p-2 text-slate-400 hover:text-blue-600 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors"
                        title="{{ __('Pengaturan') }}">
                        <span class="material-symbols-outlined text-[20px]">more_vert</span>
                    </button>
                </div>
            </header>

            <!-- Area Pesan -->
            <div id="messages" class="flex-1 overflow-y-auto p-4 md:p-6 space-y-6">
                @if(isset($conversation) && $conversation->messages->count() > 0)
                    @foreach($conversation->messages as $msg)
                        @php $isUser = $msg->role === 'user'; @endphp
                        <div class="{{ $isUser ? 'flex flex-row-reverse gap-4 max-w-3xl ml-auto' : 'flex gap-4 max-w-3xl' }}">
                            <div
                                class="h-8 w-8 rounded-full {{ $isUser ? 'bg-blue-600 text-white' : 'bg-blue-900/20 text-blue-400 border border-blue-500/20' }} flex items-center justify-center shrink-0 mt-1">
                                <span
                                    class="material-symbols-outlined text-[18px]">{{ $isUser ? 'person' : 'smart_toy' }}</span>
                            </div>
                            <div class="flex flex-col gap-1.5 {{ $isUser ? 'items-end' : '' }}">
                                <div class="flex items-baseline gap-2 {{ $isUser ? 'flex-row-reverse' : '' }}">
                                    <span
                                        class="text-sm font-semibold text-slate-900 dark:text-white">{{ $isUser ? __('Anda') : $agent->name }}</span>
                                    <span class="text-xs text-slate-500">{{ $msg->created_at->format('H:i') }}</span>
                                </div>
                                <div
                                    class="p-4 rounded-xl {{ $isUser ? 'bg-blue-600 rounded-tr-none text-white' : 'bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-tl-none text-slate-700 dark:text-slate-200 shadow-sm' }} text-sm leading-relaxed">
                                    @if($isUser)
                                        <div class="group/user flex items-start gap-4">
                                            <p class="flex-1 whitespace-pre-wrap break-words">{!! nl2br(e($msg->content)) !!}</p>
                                            <button onclick='copyTextToClipboard(this, {!! json_encode($msg->content) !!})' 
                                                class="opacity-0 group-hover/user:opacity-100 p-1 -m-1 text-white/70 hover:text-white transition-opacity shrink-0" title="Copy Prompt">
                                                <span class="material-symbols-outlined text-[16px]">content_copy</span>
                                            </button>
                                        </div>
                                    @else
                                        <div class="group/bot flex items-start gap-4">
                                            <div class="flex-1 overflow-x-auto">
                                                <div class="markdown-content" data-raw-content="{{ base64_encode($msg->content) }}"></div>
                                                @if(isset($msg->metadata['image_url']))
                                                    <div class="mt-3 group/img relative inline-block max-w-full">
                                                        <img src="{{ $msg->metadata['image_url'] }}"
                                                            class="max-w-full rounded-xl shadow-lg cursor-zoom-in block"
                                                            alt="Generated image"
                                                            onclick="openImageModal(this.src)">
                                                        <div class="absolute top-2 right-2 flex gap-1.5 opacity-0 group-hover/img:opacity-100 transition-opacity">
                                                            <button onclick="copyImageToClipboard('{{ $msg->metadata['image_url'] }}')"
                                                                class="p-1.5 bg-black/60 hover:bg-black/80 text-white rounded-lg backdrop-blur-sm" title="Copy">
                                                                <span class="material-symbols-outlined text-[16px]">content_copy</span>
                                                            </button>
                                                            <a href="{{ $msg->metadata['image_url'] }}" download="ai_image.png"
                                                                class="p-1.5 bg-black/60 hover:bg-black/80 text-white rounded-lg backdrop-blur-sm" title="Download">
                                                                <span class="material-symbols-outlined text-[16px]">download</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if(isset($msg->metadata['pdf_path']))
                                                    <div class="mt-3">
                                                        <a href="{{ route('messages.pdf', ['message' => $msg->id]) }}"
                                                            class="inline-flex items-center px-3 py-1.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 rounded-md text-xs font-medium text-blue-600 dark:text-blue-400 transition-colors gap-1">
                                                            <span class="material-symbols-outlined text-[16px]">picture_as_pdf</span>
                                                            {{ __('Unduh Laporan PDF') }}
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                            <button onclick='copyTextToClipboard(this, {!! json_encode($msg->content) !!})' 
                                                class="opacity-0 group-hover/bot:opacity-100 p-1 -m-1 text-slate-400 hover:text-blue-500 transition-opacity shrink-0" title="Copy Response">
                                                <span class="material-symbols-outlined text-[16px]">content_copy</span>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- Pesan Awal AI -->
                    <div class="flex gap-4 max-w-3xl">
                        <div
                            class="h-8 w-8 rounded-full bg-blue-900/20 flex items-center justify-center shrink-0 text-blue-400 border border-blue-500/20 mt-1">
                            <span class="material-symbols-outlined text-[18px]">smart_toy</span>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <div class="flex items-baseline gap-2">
                                <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $agent->name }}</span>
                                <span class="text-xs text-slate-500">{{ now()->format('H:i') }}</span>
                            </div>
                            <div
                                class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 rounded-xl rounded-tl-none text-slate-700 dark:text-slate-200 text-sm leading-relaxed shadow-sm">
                                <p>{{ $agent->greeting_message ?? ('Halo! Saya adalah ' . $agent->name . '. Ada yang bisa saya bantu hari ini?') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Area Input -->
            <div class="p-4 md:p-6 bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800">
                <form id="chat-form" class="relative flex flex-col gap-2">

                    @if($agent->hasCapability('image'))
                        <div
                            class="flex items-center gap-4 px-3 py-2 bg-slate-50 dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 w-fit">
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" id="image-mode-toggle"
                                    class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500 bg-white dark:bg-slate-900 transition-all cursor-pointer">
                                <span
                                    class="text-sm font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                    <span class="material-symbols-outlined text-[18px] text-blue-500">brush</span>
                                    {{ __('Buat Gambar') }}
                                </span>
                            </label>

                            <div id="image-options"
                                class="flex items-center gap-2 border-l border-slate-300 dark:border-slate-600 pl-4 hidden">
                                <label for="image-size"
                                    class="text-xs text-slate-500 dark:text-slate-400 font-semibold">{{ __('Rasio:') }}</label>
                                <select id="image-size"
                                    class="text-xs bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-700 dark:text-slate-300 py-1 pl-2 pr-6 focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer outline-none font-medium">
                                    <option value="1:1">{{ __('Square (1:1)') }}</option>
                                    <option value="16:9">{{ __('Landscape (16:9)') }}</option>
                                    <option value="9:16">{{ __('Portrait (9:16)') }}</option>
                                </select>
                            </div>
                        </div>
                    @endif

                    <div
                        class="flex items-end gap-2 bg-slate-50 dark:bg-slate-800 p-2 rounded-xl border border-slate-200 dark:border-slate-800 focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-transparent transition-all shadow-sm">
                        @csrf
                        <input type="hidden" name="conversation_id" id="conversation_id" value="">

                        <button type="button"
                            class="p-2 text-slate-400 hover:text-blue-600 dark:hover:text-white rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors shrink-0"
                            title="{{ __('Lampirkan File') }}">
                            <span class="material-symbols-outlined">attach_file</span>
                        </button>

                        <textarea id="message-input" name="content"
                            class="w-full bg-transparent border-none text-slate-900 dark:text-white placeholder-slate-400 focus:ring-0 resize-none py-2.5 max-h-32"
                            placeholder="{{ __('Ketik pesan untuk') }} {{ $agent->name }}..." rows="1"
                            required></textarea>

                        <div class="flex items-center gap-1 shrink-0 pb-0.5">
                            <button type="submit" id="send-btn"
                                class="p-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors shadow-lg shadow-blue-600/20 flex items-center justify-center"
                                title="{{ __('Kirim Pesan') }}">
                                <span class="material-symbols-outlined">send</span>
                            </button>
                        </div>
                    </div>
                </form>
                
                <!-- Quick Questions Buttons -->
                @if($agent->quick_questions && count($agent->quick_questions) > 0)
                    <div class="px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-700">
                        <div class="flex flex-wrap gap-2">
                            @foreach($agent->quick_questions as $question)
                                <button onclick="sendQuickQuestion({{ json_encode($question) }})"
                                    class="px-3 py-1.5 text-xs font-medium text-emerald-700 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/30 hover:bg-emerald-200 dark:hover:bg-emerald-800 rounded-lg transition-all border border-emerald-200 dark:border-emerald-700">
                                    {{ $question }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                <div class="text-center mt-2 pb-2">
                    <p class="text-[10px] text-slate-500">
                        {{ __('AI dapat membuat kesalahan. Pertimbangkan untuk memeriksa informasi penting.') }}
                    </p>
                </div>
            </div>
        </main>
    </div>

    <script>
        function sendQuickQuestion(question) {
            const messageInput = document.getElementById('message-input');
            messageInput.value = question;
            messageInput.style.height = 'auto';
            messageInput.style.height = messageInput.scrollHeight + 'px';
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

        function addMessage(role, content, metadata = {}) {
            const messageDiv = document.createElement('div');
            const isUser = role === 'user';

            messageDiv.className = isUser ? 'flex flex-row-reverse gap-4 max-w-3xl ml-auto' : 'flex gap-4 max-w-3xl';

            if (isUser) {
                messageDiv.innerHTML = `
                    <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center shrink-0 mt-1 text-white">
                        <span class="material-symbols-outlined text-[18px]">person</span>
                    </div>
                    <div class="flex flex-col gap-1.5 items-end">
                        <div class="flex items-baseline gap-2 flex-row-reverse">
                            <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('Anda') }}</span>
                            <span class="text-xs text-slate-500">${new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</span>
                        </div>
                        <div class="bg-blue-600 p-4 rounded-xl rounded-tr-none text-white text-sm leading-relaxed shadow-sm group/user flex items-start gap-3">
                            <p class="flex-1 whitespace-pre-wrap break-words">${escapeHtml(content)}</p>
                            <button onclick='copyTextToClipboard(this, ${JSON.stringify(content)})'
                                class="opacity-0 group-hover/user:opacity-100 p-1 -m-1 text-white/70 hover:text-white transition-opacity shrink-0" title="Copy Prompt">
                                <span class="material-symbols-outlined text-[16px]">content_copy</span>
                            </button>
                        </div>
                    </div>
                `;
            } else {
                let processedContent = parseReasoningTags(content);
                let contentHtml = `<div class="markdown-content text-sm">${renderMarkdown(processedContent)}</div>`;

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
                    contentHtml += `<div class="mt-3">
                        <a href="/messages/${metadata.message_id}/pdf" class="inline-flex items-center px-3 py-1.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 rounded-md text-xs font-medium text-blue-600 dark:text-blue-400 transition-colors gap-1">
                            <span class="material-symbols-outlined text-[16px]">picture_as_pdf</span>
                            {{ __('Unduh Laporan PDF') }}
                        </a>
                    </div>`;
                }

                messageDiv.innerHTML = `
                    <div class="h-8 w-8 rounded-full bg-blue-900/20 flex items-center justify-center shrink-0 text-blue-400 border border-blue-500/20 mt-1">
                        <span class="material-symbols-outlined text-[18px]">smart_toy</span>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <div class="flex items-baseline gap-2">
                            <span class="text-sm font-semibold text-slate-900 dark:text-white">${'{{ $agent->name }}'}</span>
                            <span class="text-xs text-slate-500">${new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</span>
                        </div>
                        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 rounded-xl rounded-tl-none text-slate-700 dark:text-slate-200 text-sm leading-relaxed shadow-sm">
                            <div class="group/bot flex items-start gap-3">
                                <div class="flex-1 overflow-x-auto">
                                    ${contentHtml}
                                </div>
                                <button onclick='copyTextToClipboard(this, ${JSON.stringify(content)})'
                                    class="opacity-0 group-hover/bot:opacity-100 p-1 -m-1 text-slate-400 hover:text-blue-500 transition-opacity shrink-0" title="Copy Response">
                                    <span class="material-symbols-outlined text-[16px]">content_copy</span>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            }

            messagesEl.appendChild(messageDiv);
            messagesEl.scrollTop = messagesEl.scrollHeight;

            if (!isUser) {
                Prism.highlightAllUnder(messageDiv);
            }
        }

        function addTypingIndicator() {
            const indicator = document.createElement('div');
            indicator.id = 'typing-indicator';
            indicator.className = 'flex gap-4 max-w-3xl';
            indicator.innerHTML = `
                <div class="h-8 w-8 rounded-full bg-blue-900/20 flex items-center justify-center shrink-0 text-blue-400 border border-blue-500/20 mt-1">
                    <span class="material-symbols-outlined text-[18px]">smart_toy</span>
                </div>
                <div class="flex items-center">
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 px-4 py-3 rounded-xl rounded-tl-none flex gap-1 items-center">
                        <div class="w-1.5 h-1.5 bg-slate-500 rounded-full animate-bounce" style="animation-delay: 0s"></div>
                        <div class="w-1.5 h-1.5 bg-slate-500 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                        <div class="w-1.5 h-1.5 bg-slate-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                    </div>
                </div>
            `;
            messagesEl.appendChild(indicator);
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

        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const content = messageInput.value.trim();
            if (!content) return;

            // Instant feedback
            addMessage('user', content);
            messageInput.value = '';
            messageInput.style.height = 'auto';
            sendBtn.disabled = true;
            messageInput.disabled = true;

            if (!conversationId) {
                try {
                    await createConversation();
                } catch (error) {
                    addMessage('assistant', '{{ __("Gagal membuat percakapan. Silakan segarkan halaman.") }}');
                    sendBtn.disabled = false;
                    messageInput.disabled = false;
                    return;
                }
            }

            addTypingIndicator();

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

            try {
                const response = await fetch('{{ route("messages.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(requestBody)
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
                addMessage('assistant', '{{ __("Maaf, terjadi kesalahan jaringan. Periksa koneksi internet Anda.") }}');
            }

            sendBtn.disabled = false;
            messageInput.disabled = false;
            messageInput.focus();
        });

        // Allow Shift+Enter for new line, Enter for submit
        messageInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                chatForm.dispatchEvent(new Event('submit'));
            }
        });

        // Parse <think> tags for UI
        function parseReasoningTags(text) {
            return text.replace(/<think>([\s\S]*?)<\/think>/g, function(match, inner) {
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
                navigator.clipboard.writeText(src).catch(() => {});
            }
        }
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeImageModal(); });
        async function copyTextToClipboard(btn, text) {
            try {
                // Determine whether to copy raw content or parsed text. 
                // We're passing the raw markdown content.
                await navigator.clipboard.writeText(text);
                const icon = btn.querySelector('span');
                const origText = icon.innerText;
                icon.innerText = 'check';
                btn.classList.add('!text-green-500');
                setTimeout(() => {
                    icon.innerText = origText;
                    btn.classList.remove('!text-green-500');
                }, 2000);
            } catch (e) {
                console.error('Copy failed:', e);
            }
        }
    </script>
</x-app-layout>

Plan: Multi-Capability AI Agent Platform (Laravel 11 + Livewire)

Summary
Build a Laravel 11 fullstack app with admin-configurable AI agents (persona, model, knowledge base, capabilities) and a Livewire-powered chat UI for end users. Integrate OpenRouter for LLMs, PostgreSQL + PGVector for RAG, and PDF/image outputs. Include Breeze auth with an admin role gate.

Assumptions and Defaults
- Use Livewire for real-time chat UI and admin screens.
- Use PostgreSQL + PGVector as the vector store.
- Use Laravel Breeze + Admin role for authentication and admin access.
- Use OpenRouter as the LLM gateway and for image generation if available; otherwise fall back to OpenAI for images.
- Use barryvdh/laravel-dompdf for PDF generation.
- Use queue workers for file ingestion and embedding generation (database or Redis queue).
- Use Markdown rendering for chat messages.

Architecture and Data Flow

High-Level Components
1. Admin Control Center
   - CRUD for agents with persona, model selection, capabilities.
   - Knowledge base uploads and processing status.
2. Chat UI
   - Agent marketplace/list.
   - Threaded conversations with markdown, images, PDF links.
3. RAG Pipeline
   - File upload to text extraction to chunking to embedding to PGVector.
   - Retrieval on user prompt to context injection to LLM response.

Primary Flows
- Agent setup: Admin creates agent, selects OpenRouter model, toggles capabilities, uploads documents.
- Knowledge ingestion: Upload, queued job extracts text, chunk + embed, store in vector table.
- Chat: User selects agent, conversation created, messages saved, LLM call, response stored, render special outputs.

Data Model (Laravel Migrations)

Tables
- agents
  - id, name, avatar_path, system_prompt, temperature, openrouter_model_id, capabilities_json, created_at, updated_at
- knowledge_sources
  - id, agent_id, file_path, status (pending|processing|ready|failed), error, created_at, updated_at
- knowledge_chunks
  - id, agent_id, knowledge_source_id, chunk_text, embedding (vector), metadata_json, created_at, updated_at
- conversations
  - id, user_id, agent_id, title, created_at, updated_at
- messages
  - id, conversation_id, role (user|assistant|system), content, metadata_json, created_at

Key Relationships
- Agent hasMany KnowledgeSource, KnowledgeChunk, Conversation
- Conversation hasMany Message
- KnowledgeSource hasMany KnowledgeChunk

Public Interfaces / APIs

Web Routes
- GET / -> agent marketplace list
- GET /agents/{agent} -> chat UI with selected agent
- POST /conversations -> start conversation
- POST /messages -> send message (Livewire action)
- GET /messages/{message}/pdf -> download PDF

Admin Routes (auth + role gate)
- GET /admin/agents
- GET /admin/agents/create
- POST /admin/agents
- GET /admin/agents/{agent}/edit
- PUT /admin/agents/{agent}
- DELETE /admin/agents/{agent}
- POST /admin/agents/{agent}/knowledge-sources (upload)
- GET /admin/agents/{agent}/knowledge-sources

Services / Interfaces
- LLMGateway (OpenRouter)
  - chat(agent, conversation, messages, context) -> assistant response
- EmbeddingService
  - embed(texts[]) -> vectors[]
- Retriever
  - search(agent_id, query, top_k) -> chunks
- PdfGenerator
  - render(template, data) -> pdf_path

Implementation Steps (Decision-Complete)
1. Bootstrap Laravel + Auth
   - Install Laravel 11, Breeze with Blade.
   - Add is_admin flag to users, gate admin routes.
2. Models/Migrations
   - Create migrations & models for Agent, KnowledgeSource, KnowledgeChunk, Conversation, Message.
   - Add PGVector extension and column types for embeddings.
3. Admin UI (Livewire)
   - Agent CRUD forms: name, avatar, system prompt, temperature, OpenRouter model, capability toggles.
   - Knowledge upload panel per agent; show processing status.
4. File Ingestion Pipeline
   - Upload controller stores file to disk.
   - Queue job extracts text (PDF/TXT/DOCX), chunks, embeds, writes knowledge_chunks.
   - Update knowledge_sources.status.
5. RAG Retrieval
   - On each user message, run vector search with top-K retrieval.
   - Inject retrieved chunks into LLM context.
6. Chat System
   - Livewire chat component with agent selection and threaded messages.
   - Persist conversations + messages.
   - Render markdown output; display images and PDF links using metadata_json.
7. LLM & Tool Handling
   - OpenRouter API client with model selection, temperature, and system prompt.
   - Capability toggles to allow image or PDF requests.
   - For PDF: generate JSON structure, render Blade template, store PDF, return link.
   - For image: call image API, store URL in message metadata.
8. PDF Generation
   - Blade templates stored under resources/views/pdf/.
   - Add route to download PDF files.
9. Observability + Error Handling
   - Log LLM call durations and errors.
   - Graceful fallback when vector retrieval fails.
10. Polish
   - Agent marketplace UI and chat UX.
   - Empty states and loading indicators.

Edge Cases and Failure Modes
- No retrieved context: LLM uses system prompt only.
- Failed file extraction: mark knowledge_sources.status=failed and show error.
- Unsupported file types: reject at upload.
- Image/PDF capability disabled: tool requests ignored with user-visible message.
- LLM rate limits: return retry message and log.

Testing and Acceptance Criteria

Automated Tests
- Model tests
  - Agent CRUD + relationships.
- Feature tests
  - Admin can create/edit agent.
  - Knowledge upload triggers queued job.
  - Chat message creates assistant response record.
  - PDF download route returns file.
- Integration tests
  - Embedding storage with PGVector.
  - Retrieval returns top-k chunks.
- Livewire tests
  - Chat component renders and posts messages.

Acceptance Criteria
- Admin can create agent with persona, model, and capabilities.
- Knowledge file upload results in searchable RAG context.
- User chat returns responses in <3s for text-only calls (per PRD).
- Image responses render in chat when enabled.
- PDF links are generated and downloadable when enabled.

Open Questions Resolved
- Frontend: Livewire.
- Vector DB: Postgres + PGVector.
- Auth: Breeze + Admin role.

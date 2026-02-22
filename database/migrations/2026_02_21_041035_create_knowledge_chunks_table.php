<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('knowledge_chunks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained()->onDelete('cascade');
            $table->foreignId('knowledge_source_id')->constrained()->onDelete('cascade');
            $table->text('chunk_text');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        DB::statement('CREATE EXTENSION IF NOT EXISTS vector;');
        DB::statement('ALTER TABLE knowledge_chunks ADD COLUMN embedding vector(1536);');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('knowledge_chunks');
    }
};

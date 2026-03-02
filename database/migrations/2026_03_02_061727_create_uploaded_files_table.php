<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('uploaded_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->onDelete('cascade');
            $table->foreignId('message_id')->constrained()->onDelete('cascade');
            $table->string('original_filename');
            $table->string('stored_filename');
            $table->string('file_path');
            $table->string('mime_type');
            $table->unsignedInteger('file_size'); // in bytes
            $table->enum('file_type', ['pdf', 'excel', 'word', 'text', 'image', 'other']);
            $table->text('analysis_summary')->nullable(); // Store brief analysis result
            $table->timestamps();

            $table->index('conversation_id');
            $table->index('file_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uploaded_files');
    }
};

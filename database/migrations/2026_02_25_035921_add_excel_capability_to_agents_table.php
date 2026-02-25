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
        Schema::table('agents', function (Blueprint $table) {
            // Excel generation capability
            $table->boolean('can_generate_excel')->default(false);
            
            // Quick questions/templates for chatbox
            $table->json('quick_questions')->nullable();
            // Example: ["Hitung Profit First saya", "Buat Excel lengkap", "Analisis OPEX"]
            
            // Default greeting message
            $table->text('greeting_message')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->dropColumn(['can_generate_excel', 'quick_questions', 'greeting_message']);
        });
    }
};

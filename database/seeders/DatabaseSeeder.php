<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::firstOrCreate([
            'email' => 'admin@aiagent.com'
        ], [
            'name' => 'Admin User',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        // Create Regular User
        User::firstOrCreate([
            'email' => 'user@aiagent.com'
        ], [
            'name' => 'Regular User',
            'password' => bcrypt('password'),
            'is_admin' => false,
        ]);

        // Create Some Initial Agents
        \App\Models\Agent::firstOrCreate([
            'name' => 'General Assistant'
        ], [
            'openrouter_model_id' => 'openai/gpt-4o-mini',
            'system_prompt' => 'You are a helpful, smart, and concise AI assistant.',
            'capabilities' => ['text'],
            'is_active' => true,
        ]);

        \App\Models\Agent::firstOrCreate([
            'name' => 'Data Analyst'
        ], [
            'openrouter_model_id' => 'anthropic/claude-3.5-sonnet',
            'system_prompt' => 'You are an expert data analyst. You help users understand their data, create summaries, and output findings in structured formats.',
            'capabilities' => ['text', 'pdf_export'],
            'is_active' => true,
        ]);

        \App\Models\Agent::firstOrCreate([
            'name' => 'Creative Designer'
        ], [
            'openrouter_model_id' => 'meta-llama/llama-3-70b-instruct',
            'system_prompt' => 'You are a highly creative designer who loves brainstorming visual ideas and generating impressive image prompts.',
            'capabilities' => ['text', 'image_generation'],
            'is_active' => true,
        ]);
    }
}

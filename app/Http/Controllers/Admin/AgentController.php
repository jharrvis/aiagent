<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\KnowledgeSource;
use App\Jobs\ProcessKnowledgeSource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AgentController extends Controller
{
    public function index()
    {
        $agents = Agent::withCount('knowledgeSources')->latest()->get();

        return view('admin.agents.index', compact('agents'));
    }

    public function create()
    {
        $models = config('services.openrouter.models', []);

        return view('admin.agents.create', compact('models'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'system_prompt' => 'nullable|string',
            'temperature' => 'nullable|numeric|min:0|max:2',
            'openrouter_model_id' => 'required|string',
            'avatar' => 'nullable|image|max:2048',
            'capabilities' => 'nullable|array',
            'can_generate_excel' => 'nullable|boolean',
            'quick_questions' => 'nullable|string',
            'greeting_message' => 'nullable|string',
        ]);

        $data = $request->only([
            'name',
            'description',
            'system_prompt',
            'temperature',
            'openrouter_model_id',
            'capabilities',
            'can_generate_excel',
            'greeting_message',
        ]);

        // Parse quick questions
        if ($request->filled('quick_questions')) {
            $questions = array_filter(array_map('trim', explode("\n", $request->quick_questions)));
            $data['quick_questions'] = $questions;
        }

        if ($request->hasFile('avatar')) {
            $data['avatar_path'] = $request->file('avatar')->store('avatars', 'public');
        }

        $data['capabilities'] = $request->capabilities ?? [];
        
        // Auto-add 'excel' capability if can_generate_excel is enabled
        if ($request->boolean('can_generate_excel') && !in_array('excel', $data['capabilities'])) {
            $data['capabilities'][] = 'excel';
        }

        Agent::create($data);

        return redirect()->route('admin.agents.index')
            ->with('success', 'Agent created successfully.');
    }

    public function show(Agent $agent)
    {
        $agent->load([
            'knowledgeSources' => function ($query) {
                $query->latest();
            }
        ]);

        return view('admin.agents.show', compact('agent'));
    }

    public function edit(Agent $agent)
    {
        $models = config('services.openrouter.models', []);

        return view('admin.agents.edit', compact('agent', 'models'));
    }

    public function update(Request $request, Agent $agent)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'system_prompt' => 'nullable|string',
            'temperature' => 'nullable|numeric|min:0|max:2',
            'openrouter_model_id' => 'required|string',
            'avatar' => 'nullable|image|max:2048',
            'capabilities' => 'nullable|array',
            'can_generate_excel' => 'nullable|boolean',
            'quick_questions' => 'nullable|string',
            'greeting_message' => 'nullable|string',
        ]);

        $data = $request->only([
            'name',
            'description',
            'system_prompt',
            'temperature',
            'openrouter_model_id',
            'capabilities',
            'can_generate_excel',
            'greeting_message',
        ]);

        // Parse quick questions
        if ($request->filled('quick_questions')) {
            $questions = array_filter(array_map('trim', explode("\n", $request->quick_questions)));
            $data['quick_questions'] = $questions;
        }

        if ($request->hasFile('avatar')) {
            if ($agent->avatar_path) {
                Storage::disk('public')->delete($agent->avatar_path);
            }
            $data['avatar_path'] = $request->file('avatar')->store('avatars', 'public');
        }

        $data['capabilities'] = $request->capabilities ?? [];
        
        // Auto-add 'excel' capability if can_generate_excel is enabled
        if ($request->boolean('can_generate_excel') && !in_array('excel', $data['capabilities'])) {
            $data['capabilities'][] = 'excel';
        } elseif (!$request->boolean('can_generate_excel')) {
            // Remove 'excel' capability if can_generate_excel is disabled
            $data['capabilities'] = array_values(array_diff($data['capabilities'], ['excel']));
        }

        $agent->update($data);

        return redirect()->route('admin.agents.index')
            ->with('success', 'Agent updated successfully.');
    }

    public function destroy(Agent $agent)
    {
        if ($agent->avatar_path) {
            Storage::disk('public')->delete($agent->avatar_path);
        }

        $agent->delete();

        return redirect()->route('admin.agents.index')
            ->with('success', 'Agent deleted successfully.');
    }

    /**
     * Toggle agent active status
     */
    public function toggleStatus(Agent $agent)
    {
        $agent->update(['is_active' => !$agent->is_active]);

        $status = $agent->is_active ? 'active' : 'inactive';

        return redirect()->route('admin.agents.index')
            ->with('success', "Agent {$status} successfully.");
    }

    public function uploadKnowledge(Request $request, Agent $agent)
    {
        $request->validate([
            'files' => 'required|array|min:1',
            'files.*' => 'file|mimes:pdf,txt,docx|max:10240',
        ]);

        $uploadedCount = 0;
        $failedFiles = [];

        foreach ($request->file('files') as $file) {
            try {
                $path = $file->store('knowledge', 'local');
                $originalFilename = $file->getClientOriginalName();

                $knowledgeSource = KnowledgeSource::create([
                    'agent_id' => $agent->id,
                    'file_path' => $path,
                    'original_filename' => $originalFilename,
                    'status' => 'pending',
                ]);

                ProcessKnowledgeSource::dispatch($knowledgeSource);
                $uploadedCount++;
            } catch (\Exception $e) {
                $failedFiles[] = $file->getClientOriginalName();
                \Log::error('Knowledge source upload failed', [
                    'file' => $file->getClientOriginalName(),
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $message = $uploadedCount > 0 
            ? "{$uploadedCount} file(s) uploaded and processing started." 
            : 'No files were uploaded.';
        
        if (!empty($failedFiles)) {
            $message .= " Failed: " . implode(', ', $failedFiles);
        }

        return back()->with($uploadedCount > 0 ? 'success' : 'error', $message);
    }
}

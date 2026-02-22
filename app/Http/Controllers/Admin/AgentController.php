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
        ]);

        $data = $request->only([
            'name',
            'description',
            'system_prompt',
            'temperature',
            'openrouter_model_id',
            'capabilities'
        ]);

        if ($request->hasFile('avatar')) {
            $data['avatar_path'] = $request->file('avatar')->store('avatars', 'public');
        }

        $data['capabilities'] = $request->capabilities ?? [];

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
        ]);

        $data = $request->only([
            'name',
            'description',
            'system_prompt',
            'temperature',
            'openrouter_model_id',
            'capabilities'
        ]);

        if ($request->hasFile('avatar')) {
            if ($agent->avatar_path) {
                Storage::disk('public')->delete($agent->avatar_path);
            }
            $data['avatar_path'] = $request->file('avatar')->store('avatars', 'public');
        }

        $data['capabilities'] = $request->capabilities ?? [];

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

    public function uploadKnowledge(Request $request, Agent $agent)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,txt,docx|max:10240',
        ]);

        $file = $request->file('file');
        $path = $file->store('knowledge', 'local');
        $originalFilename = $file->getClientOriginalName();

        $knowledgeSource = KnowledgeSource::create([
            'agent_id' => $agent->id,
            'file_path' => $path,
            'original_filename' => $originalFilename,
            'status' => 'pending',
        ]);

        ProcessKnowledgeSource::dispatch($knowledgeSource);

        return back()->with('success', 'File uploaded and processing started.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\ExcelTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExcelTemplateController extends Controller
{
    /**
     * Display a listing of excel templates.
     */
    public function index(Request $request)
    {
        $agentId = $request->input('agent_id');
        
        $query = ExcelTemplate::with('agent');
        
        if ($agentId) {
            $query->where('agent_id', $agentId);
        }
        
        $templates = $query->latest()->get();
        $agents = Agent::all();
        
        return view('admin.excel-templates.index', compact('templates', 'agents'));
    }

    /**
     * Show the form for creating a new excel template.
     */
    public function create()
    {
        $agents = Agent::all();
        return view('admin.excel-templates.create', compact('agents'));
    }

    /**
     * Store a newly created excel template in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|exists:agents,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:xlsx,xlsm,xltx|max:10240',
            'category' => 'nullable|string|max:100',
            'variables' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        // Upload file
        $file = $request->file('file');
        $path = $file->store('excel-templates', 'public');
        
        // Parse variables from JSON string
        $variables = null;
        if ($request->filled('variables')) {
            $variables = json_decode($request->variables, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $variables = array_map('trim', explode(',', $request->variables));
            }
        }

        ExcelTemplate::create([
            'agent_id' => $request->agent_id,
            'name' => $request->name,
            'description' => $request->description,
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'category' => $request->category,
            'variables' => $variables,
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.excel-templates.index')
            ->with('success', 'Excel template uploaded successfully.');
    }

    /**
     * Display the specified excel template.
     */
    public function show(ExcelTemplate $excelTemplate)
    {
        return response()->json($excelTemplate);
    }

    /**
     * Show the form for editing the specified excel template.
     */
    public function edit(ExcelTemplate $excelTemplate)
    {
        $agents = Agent::all();
        return view('admin.excel-templates.edit', compact('excelTemplate', 'agents'));
    }

    /**
     * Update the specified excel template in storage.
     */
    public function update(Request $request, ExcelTemplate $excelTemplate)
    {
        $request->validate([
            'agent_id' => 'required|exists:agents,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:xlsx,xlsm,xltx|max:10240',
            'category' => 'nullable|string|max:100',
            'variables' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $data = [
            'agent_id' => $request->agent_id,
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $request->sort_order ?? 0,
        ];

        // Parse variables
        if ($request->filled('variables')) {
            $variables = json_decode($request->variables, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $variables = array_map('trim', explode(',', $request->variables));
            }
            $data['variables'] = $variables;
        }

        // Upload new file if provided
        if ($request->hasFile('file')) {
            // Delete old file
            if ($excelTemplate->file_path) {
                Storage::disk('public')->delete($excelTemplate->file_path);
            }
            
            $file = $request->file('file');
            $path = $file->store('excel-templates', 'public');
            $data['file_path'] = $path;
            $data['original_filename'] = $file->getClientOriginalName();
        }

        $excelTemplate->update($data);

        return redirect()->route('admin.excel-templates.index')
            ->with('success', 'Excel template updated successfully.');
    }

    /**
     * Remove the specified excel template from storage.
     */
    public function destroy(ExcelTemplate $excelTemplate)
    {
        // Delete file
        if ($excelTemplate->file_path) {
            Storage::disk('public')->delete($excelTemplate->file_path);
        }
        
        $excelTemplate->delete();

        return redirect()->route('admin.excel-templates.index')
            ->with('success', 'Excel template deleted successfully.');
    }

    /**
     * Download template file
     */
    public function download(ExcelTemplate $excelTemplate)
    {
        return Storage::disk('public')->download($excelTemplate->file_path);
    }
}

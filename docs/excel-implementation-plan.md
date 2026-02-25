# 📊 Excel Generation & Quick Questions - Implementation Plan

## ✅ **Completed (Sudah Dikerjakan)**

### 1. Database Migrations
- ✅ `add_excel_capability_to_agents_table` - Add columns to agents table
- ✅ `create_excel_templates_table` - Create Excel templates table

### 2. Models
- ✅ `app/Models/ExcelTemplate.php` - Excel template model
- ✅ Updated `app/Models/Agent.php` - Add Excel relationships

### 3. Services
- ✅ `app/Services/ExcelGenerator.php` - Excel generation service with:
  - Template-based generation
  - Profit First workbook creation (7 sheets)
  - Dynamic cell filling

### 4. Controllers
- ✅ `app/Controllers/Admin/ExcelTemplateController.php` - Full CRUD for templates

### 5. Dependencies
- ✅ Installed `phpoffice/phpspreadsheet`

---

## 📝 **TODO - Remaining Implementation**

### **Step 1: Update Routes**

Add to `routes/web.php`:

```php
// Excel Template Management
Route::resource('excel-templates', \App\Http\Controllers\Admin\ExcelTemplateController::class)
    ->middleware(['auth', 'admin']);
Route::get('excel-templates/{excelTemplate}/download', 
    [\App\Http\Controllers\Admin\ExcelTemplateController::class, 'download'])
    ->name('excel-templates.download')
    ->middleware(['auth', 'admin']);
```

---

### **Step 2: Create Views**

#### **2.1 Excel Templates Management**

Create folder: `resources/views/admin/excel-templates/`

**File: `index.blade.php`** - List Templates

```blade
<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
                        {{ __('Excel Templates') }}
                    </h1>
                    <p class="text-slate-500 dark:text-slate-400 mt-1">
                        {{ __('Kelola template Excel untuk AI agents') }}
                    </p>
                </div>
                <a href="{{ route('admin.excel-templates.create') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-semibold transition-all">
                    <span class="material-symbols-outlined">upload_file</span>
                    {{ __('Upload Template') }}
                </a>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-100 dark:bg-emerald-900/30 border-l-4 border-emerald-500 text-emerald-700 dark:text-emerald-400 rounded-r-xl">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700">
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Name</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Agent</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Category</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Variables</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @forelse($templates as $template)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-semibold text-slate-900 dark:text-white">{{ $template->name }}</p>
                                        <p class="text-xs text-slate-500">{{ $template->original_filename }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-slate-600 dark:text-slate-400">{{ $template->agent->name }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400">
                                        {{ $template->category ?? 'General' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach(($template->variables ?? []) as $var)
                                            <span class="px-2 py-0.5 text-xs bg-slate-100 dark:bg-slate-800 rounded">{{ $var }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($template->is_active)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400">
                                            Active
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.excel-templates.download', $template) }}"
                                            class="p-2 text-slate-400 hover:text-blue-600" title="Download">
                                            <span class="material-symbols-outlined">download</span>
                                        </a>
                                        <a href="{{ route('admin.excel-templates.edit', $template) }}"
                                            class="p-2 text-slate-400 hover:text-emerald-600" title="Edit">
                                            <span class="material-symbols-outlined">edit</span>
                                        </a>
                                        <form action="{{ route('admin.excel-templates.destroy', $template) }}" 
                                            method="POST" 
                                            onsubmit="return confirm('Delete this template?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="p-2 text-slate-400 hover:text-red-600" title="Delete">
                                                <span class="material-symbols-outlined">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                    No templates uploaded yet
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
```

**File: `create.blade.php`** - Upload Form

```blade
<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-6">
                {{ __('Upload Excel Template') }}
            </h1>

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm p-8">
                <form action="{{ route('admin.excel-templates.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Agent Selection -->
                    <div class="mb-6">
                        <label for="agent_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                            {{ __('Assign to Agent') }} *
                        </label>
                        <select name="agent_id" id="agent_id" required
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none">
                            <option value="">Select Agent</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Template Name -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                            {{ __('Template Name') }} *
                        </label>
                        <input type="text" name="name" id="name" required
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none">
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                            {{ __('Description') }}
                        </label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none"></textarea>
                    </div>

                    <!-- Excel File -->
                    <div class="mb-6">
                        <label for="file" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                            {{ __('Excel File') }} *
                        </label>
                        <input type="file" name="file" id="file" accept=".xlsx,.xlsm,.xltx" required
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none">
                        <p class="mt-2 text-xs text-slate-500">Max file size: 10MB</p>
                    </div>

                    <!-- Category -->
                    <div class="mb-6">
                        <label for="category" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                            {{ __('Category') }}
                        </label>
                        <input type="text" name="category" id="category" placeholder="e.g., profit_first, franchise, inventory"
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none">
                    </div>

                    <!-- Variables (JSON) -->
                    <div class="mb-6">
                        <label for="variables" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                            {{ __('Variables') }}
                        </label>
                        <textarea name="variables" id="variables" rows="3"
                            placeholder='["omzet", "profit_percent", "opex_percent"]'
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none font-mono text-sm"></textarea>
                        <p class="mt-2 text-xs text-slate-500">Comma-separated or JSON array</p>
                    </div>

                    <!-- Is Active -->
                    <div class="mb-6">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="is_active" value="1" checked
                                class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            <span class="text-sm text-slate-600 dark:text-slate-400">{{ __('Active') }}</span>
                        </label>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.excel-templates.index') }}"
                            class="px-6 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl font-semibold transition-all">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit"
                            class="px-6 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-semibold transition-all">
                            {{ __('Upload Template') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
```

---

### **Step 3: Update Agent Edit Form**

Add to `resources/views/admin/agents/edit.blade.php`:

```blade
<!-- Excel Capability Section -->
<div class="mb-6">
    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">
        {{ __('Excel Generation') }}
    </h3>
    
    <!-- Can Generate Excel -->
    <div class="mb-4">
        <label class="flex items-center gap-2">
            <input type="checkbox" name="can_generate_excel" value="1" 
                {{ old('can_generate_excel', $agent->can_generate_excel ?? false) ? 'checked' : '' }}
                class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
            <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                {{ __('Enable Excel Generation') }}
            </span>
        </label>
    </div>

    <!-- Quick Questions -->
    <div class="mb-4">
        <label for="quick_questions" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
            {{ __('Quick Questions (Clickable Buttons)') }}
        </label>
        <textarea name="quick_questions" id="quick_questions" rows="5"
            placeholder="{{ __('Enter one question per line') }}&#10;Hitung Profit First saya&#10;Buat Excel lengkap&#10;Analisis OPEX"
            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none font-mono text-sm">{{ old('quick_questions', is_array($agent->quick_questions ?? []) ? implode("\n", $agent->quick_questions) : '') }}</textarea>
        <p class="mt-2 text-xs text-slate-500">{{ __('One question per line. These will appear as clickable buttons in chat.') }}</p>
    </div>

    <!-- Greeting Message -->
    <div class="mb-4">
        <label for="greeting_message" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
            {{ __('Greeting Message') }}
        </label>
        <textarea name="greeting_message" id="greeting_message" rows="3"
            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none">{{ old('greeting_message', $agent->greeting_message ?? '') }}</textarea>
        <p class="mt-2 text-xs text-slate-500">{{ __('Shown when user starts new conversation') }}</p>
    </div>
</div>
```

---

### **Step 4: Update Chat UI**

Add to `resources/views/chat.blade.php`:

```blade
<!-- Quick Questions Buttons -->
@if($agent->quick_questions && count($agent->quick_questions) > 0)
    <div class="px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-700">
        <div class="flex flex-wrap gap-2">
            @foreach($agent->quick_questions as $question)
                <button onclick="sendQuickQuestion({{ json_encode($question) }})"
                    class="px-3 py-1.5 text-xs font-medium text-emerald-700 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/30 hover:bg-emerald-200 dark:hover:bg-emerald-800 rounded-lg transition-all">
                    {{ $question }}
                </button>
            @endforeach
        </div>
    </div>
@endif

<script>
function sendQuickQuestion(question) {
    document.getElementById('message-input').value = question;
    document.getElementById('chat-form').dispatchEvent(new Event('submit', { cancelable: true }));
}
</script>
```

---

### **Step 5: Update Message Controller**

Add Excel handling to `app/Http/Controllers/MessageController.php`:

```php
use App\Services\ExcelGenerator;

public function __construct(
    private LLMService $llmService,
    private RAGService $ragService,
    private ExcelGenerator $excelGenerator
) {}

// In store() method, after getting LLM response:
$metadata = [];

// Check for Excel request
if ($conversation->agent->can_generate_excel && $this->needsExcel($request->input('content'))) {
    $excelUrl = $this->generateExcel($conversation->agent, $llmResponse['content'], $request->input('content'));
    $metadata['excel_path'] = $excelUrl;
}

// ... existing image/pdf code ...

private function needsExcel(string $content): bool
{
    $content = strtolower($content);
    return str_contains($content, 'excel')
        || str_contains($content, 'spreadsheet')
        || str_contains($content, 'file excel')
        || str_contains($content, 'buat excel')
        || str_contains($content, 'download');
}

private function generateExcel(Agent $agent, string $aiResponse, string $userPrompt): string
{
    // Get agent's default template
    $template = $agent->excelTemplates()->first();
    
    if (!$template) {
        // Fallback to Profit First template
        $data = $this->parseFinancialData($aiResponse);
        return $this->excelGenerator->createProfitFirstWorkbook($data);
    }
    
    // Use template
    $data = $this->parseFinancialData($aiResponse);
    return $this->excelGenerator->generateFromTemplate($template->file_path, $data);
}

private function parseFinancialData(string $content): array
{
    // Extract numbers from AI response
    preg_match('/omzet.*?(\d+(?:[.,]\d+)*)/i', $content, $omzetMatches);
    preg_match('/profit.*?(\d+)%/i', $content, $profitMatches);
    
    return [
        'omzet' => (float) str_replace(',', '', $omzetMatches[1] ?? 0),
        'profit_percent' => $profitMatches[1] ?? '5',
        'owner_pay_percent' => '50',
        'tax_percent' => '15',
        'opex_percent' => '30',
    ];
}
```

---

### **Step 6: Add Excel Download Button to Chat**

Add to chat message display section:

```blade
@if(isset($msg->metadata['excel_path']))
    <div class="mt-3">
        <a href="{{ $msg->metadata['excel_path'] }}"
            class="inline-flex items-center px-3 py-1.5 bg-green-100 dark:bg-green-900/30 hover:bg-green-200 dark:hover:bg-green-800 border border-green-200 dark:border-green-700 rounded-md text-xs font-medium text-green-700 dark:text-green-400 transition-colors gap-1">
            <span class="material-symbols-outlined text-[16px]">table_chart</span>
            {{ __('Unduh File Excel') }}
        </a>
    </div>
@endif
```

---

## 🎯 **Testing Checklist**

- [ ] Upload Excel template via admin
- [ ] Assign template to agent
- [ ] Set quick questions for agent
- [ ] Test chat with quick question buttons
- [ ] Request Excel generation via chat
- [ ] Download generated Excel file
- [ ] Verify Excel data is correct

---

## 📚 **Next Steps**

Mau saya lanjutkan dengan:
1. **Buat semua view files** yang belum?
2. **Update MessageController** dengan Excel handling?
3. **Test end-to-end flow**?

Atau Anda mau develop sisanya sendiri dengan panduan ini? 🚀

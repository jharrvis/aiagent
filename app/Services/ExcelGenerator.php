<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ExcelGenerator
{
    /**
     * Load template and fill with data
     */
    public function generateFromTemplate(string $templatePath, array $data, string $prefix = 'generated'): string
    {
        // Load template
        $spreadsheet = IOFactory::load(storage_path('app/public/' . $templatePath));
        
        // Fill data into cells
        foreach ($data as $cell => $value) {
            // Get active sheet
            $sheet = $spreadsheet->getActiveSheet();
            
            // Set cell value
            $sheet->setCellValue($cell, $value);
        }
        
        // Generate filename
        $filename = $prefix . '/' . Str::random(10) . '_' . time() . '.xlsx';
        
        // Save file
        $writer = new Xlsx($spreadsheet);
        $path = Storage::disk('public')->path($filename);
        
        // Create directory if not exists
        $directory = dirname($path);
        if (!Storage::disk('public')->exists(dirname($filename))) {
            Storage::disk('public')->makeDirectory(dirname($filename));
        }
        
        $writer->save($path);
        
        // Return URL
        return Storage::disk('public')->url($filename);
    }
    
    /**
     * Create Profit First Excel from scratch
     */
    public function createProfitFirstWorkbook(array $data): string
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        
        // Remove default sheet
        $spreadsheet->removeSheetByIndex(0);
        
        // Create all sheets
        $this->createSetupSheet($spreadsheet, $data);
        $this->createDailyIncomeSheet($spreadsheet);
        $this->createMonthlySummarySheet($spreadsheet, $data);
        $this->createOPEXSheet($spreadsheet);
        $this->createBudgetPlanSheet($spreadsheet, $data);
        $this->createReconciliationSheet($spreadsheet);
        $this->createDashboardSheet($spreadsheet, $data);
        
        // Save file
        $filename = 'profit-first/profit_first_' . time() . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        
        $path = Storage::disk('public')->path($filename);
        
        // Create directory
        if (!Storage::disk('public')->exists('profit-first')) {
            Storage::disk('public')->makeDirectory('profit-first');
        }
        
        $writer->save($path);
        
        return Storage::disk('public')->url($filename);
    }
    
    private function createSetupSheet($spreadsheet, $data)
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Setup');
        
        // Headers
        $sheet->setCellValue('A1', 'AKUN PROFIT FIRST');
        $sheet->setCellValue('B1', 'PERSENTASE');
        
        // Setup accounts
        $accounts = [
            ['INCOME', '100%'],
            ['PROFIT', $data['profit_percent'] ?? '5%'],
            ['OWNER PAY', $data['owner_pay_percent'] ?? '50%'],
            ['TAX', $data['tax_percent'] ?? '15%'],
            ['OPEX', $data['opex_percent'] ?? '30%'],
        ];
        
        $sheet->fromArray($accounts, null, 'A3');
        
        // Styling
        $sheet->getStyle('A1:B1')->getFont()->setBold(true);
        $sheet->getStyle('A1:B1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('4299E1');
        $sheet->getStyle('A1:B1')->getFont()->getColor()->setRGB('FFFFFF');
        
        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(15);
    }
    
    private function createDailyIncomeSheet($spreadsheet)
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Daily Income');
        
        // Headers
        $headers = [
            ['Tanggal', 'Omzet', 'Profit (5%)', 'Owner Pay (50%)', 'Tax (15%)', 'OPEX (30%)'],
        ];
        
        $sheet->fromArray($headers, null, 'A1');
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        
        // Add formulas for 31 days
        for ($row = 2; $row <= 32; $row++) {
            $sheet->setCellValue("A{$row}", "=DATE(2026," . (int)ceil(($row-1)/31) . "," . ($row-1) . ")");
            $sheet->setCellValue("B{$row}", 0);
            $sheet->setCellValue("C{$row}", "=B{$row}*0.05");
            $sheet->setCellValue("D{$row}", "=B{$row}*0.50");
            $sheet->setCellValue("E{$row}", "=B{$row}*0.15");
            $sheet->setCellValue("F{$row}", "=B{$row}*0.30");
        }
        
        // Auto-width columns
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
    
    private function createMonthlySummarySheet($spreadsheet, $data)
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Monthly Summary');
        
        $summary = [
            ['Summary Bulanan'],
            ['Omzet Total', $data['omzet'] ?? 0],
            ['Profit (5%)', '=B2*0.05'],
            ['Owner Pay (50%)', '=B2*0.50'],
            ['Tax (15%)', '=B2*0.15'],
            ['OPEX (30%)', '=B2*0.30'],
        ];
        
        $sheet->fromArray($summary, null, 'A1');
        $sheet->getStyle('A1:B1')->getFont()->setBold(true);
    }
    
    private function createOPEXSheet($spreadsheet)
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('OPEX Tracker');
        
        $headers = [
            ['Tanggal', 'Kategori', 'Deskripsi', 'Jumlah', 'Catatan'],
        ];
        
        $sheet->fromArray($headers, null, 'A1');
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
        
        // Add 50 empty rows for data entry
        for ($row = 2; $row <= 52; $row++) {
            $sheet->setCellValue("A{$row}", '');
            $sheet->setCellValue("B{$row}", '');
            $sheet->setCellValue("C{$row}", '');
            $sheet->setCellValue("D{$row}", 0);
            $sheet->setCellValue("E{$row}", '');
        }
        
        // Add total formula
        $sheet->setCellValue('D53', '=SUM(D2:D52)');
        $sheet->setCellValue('C53', 'TOTAL:');
        $sheet->getStyle('C53:D53')->getFont()->setBold(true);
    }
    
    private function createBudgetPlanSheet($spreadsheet, $data)
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Budget Plan');
        
        $budget = [
            ['Rencana Anggaran Bulanan'],
            ['Kategori', 'Budget', 'Realisasi', 'Selisih'],
            ['Bahan Baku', 0, 0, '=B3-C3'],
            ['Gaji Karyawan', 0, 0, '=B4-C4'],
            ['Sewa Tempat', 0, 0, '=B5-C5'],
            ['Listrik & Air', 0, 0, '=B6-C6'],
            ['Marketing', 0, 0, '=B7-C7'],
            ['Lainnya', 0, 0, '=B8-C8'],
            ['TOTAL', '=SUM(B3:B8)', '=SUM(C3:C8)', '=SUM(D3:D8)'],
        ];
        
        $sheet->fromArray($budget, null, 'A1');
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);
        $sheet->getStyle('A2:D2')->getFont()->setBold(true);
    }
    
    private function createReconciliationSheet($spreadsheet)
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Reconciliation');
        
        $recon = [
            ['Rekonsiliasi Bulanan'],
            ['Ritme: Tanggal 10 & 25 setiap bulan'],
            [''],
            ['Bulan', 'Profit Transfer', 'Owner Pay Transfer', 'Tax Transfer', 'OPEX Transfer'],
        ];
        
        $sheet->fromArray($recon, null, 'A1');
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);
        
        // Add 12 months
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        foreach ($months as $index => $month) {
            $row = $index + 5;
            $sheet->setCellValue("A{$row}", $month);
        }
    }
    
    private function createDashboardSheet($spreadsheet, $data)
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Dashboard');
        
        $dashboard = [
            ['Dashboard Keuangan'],
            [''],
            ['Total Omzet', $data['omzet'] ?? 0],
            ['Total Profit', '=\'Monthly Summary\'!B3'],
            ['Total Owner Pay', '=\'Monthly Summary\'!B4'],
            ['Total Tax', '=\'Monthly Summary\'!B5'],
            ['Total OPEX', '=\'Monthly Summary\'!B6'],
            [''],
            ['Persentase'],
            ['Profit', '5%'],
            ['Owner Pay', '50%'],
            ['Tax', '15%'],
            ['OPEX', '30%'],
        ];
        
        $sheet->fromArray($dashboard, null, 'A1');
        $sheet->getStyle('A1:B1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A3:B7')->getFont()->setBold(true);
        $sheet->getStyle('A3:B7')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('E6FFFA');
    }
}

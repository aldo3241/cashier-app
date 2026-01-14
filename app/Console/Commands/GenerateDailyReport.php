<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Penjualan;
use App\Models\LaporanKasir;
use Carbon\Carbon;

class GenerateDailyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sales:generate-daily-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate daily sales report for LaporanKasir';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Default to today if running at 23:59, or allow a date argument in future?
        // For cron running at 23:59 or 00:00, let's assume "Today" if 23:59, "Yesterday" if 00:00.
        // But better is to just pick "Yesterday" if running at 00:01.
        // Or if running at 23:59, it's "Today".
        // Let's assume the scheduler runs it at 23:59:00.
        
        $date = Carbon::now();
        $this->info("Generating report for: " . $date->toDateString());

        $startOfDay = $date->copy()->startOfDay();
        $endOfDay = $date->copy()->endOfDay();

        // 1. Calculate Income (Sales)
        $income = Penjualan::whereBetween('date_created', [$startOfDay, $endOfDay])
            ->where('status_bayar', 'Lunas')
            ->sum('total_bayar');

        // 2. Calculate Expense (Placeholder or Real if exists)
        $expense = 0; // Currently no expense tracking logic provided
        
        $grossProfit = $income - $expense;

        // 3. Update or Create Report
        // Delete existing valid report for this timeframe to ensure no duplicates
        LaporanKasir::whereBetween('mulai', [$startOfDay, $endOfDay])->delete();

        LaporanKasir::create([
            'mulai' => $startOfDay,
            'akhir' => $endOfDay,
            'pemasukkan' => $income,
            'koreksi_pemasukkan' => 0,
            'pengeluaran' => $expense,
            'koreksi_pengeluaran' => 0,
            'laba_kotor' => $grossProfit
        ]);

        $this->info("Report generated. Income: $income");
        
        return 0;
    }
}

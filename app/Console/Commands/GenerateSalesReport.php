<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Keuangan;
use App\Models\LaporanKasir;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GenerateSalesReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sales:generate-report {--start= : Start date (YYYY-MM-DD)} {--end= : End date (YYYY-MM-DD)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate LaporanKasir data from Keuangan table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $startDate = $this->option('start') ? Carbon::parse($this->option('start')) : Carbon::now()->startOfMonth();
        $endDate = $this->option('end') ? Carbon::parse($this->option('end')) : Carbon::now();

        $this->info("Generating report from {$startDate->toDateString()} to {$endDate->toDateString()}");

        // Get distinct dates from Keuangan within range
        $dates = Keuangan::select(DB::raw('DATE(date_created) as date'))
            ->whereBetween('date_created', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->groupBy('date')
            ->pluck('date');

        $bar = $this->output->createProgressBar(count($dates));
        $bar->start();

        foreach ($dates as $dateStr) {
            $date = Carbon::parse($dateStr);
            
            // Calculate stats for this day
            $income = Keuangan::whereDate('date_created', $date)
                ->where('keuangan_kategori', 'Penjualan')
                ->sum('masuk');

            // Find other income (if any, though 'masuk' usually implies income)
            // For now, let's assume all 'masuk' is income, but split if needed?
            // The user wanted 'Pemasukkan' based on sales.
            // Let's stick to: Total Masuk = Pemasukkan.
            
            $totalIncome = Keuangan::whereDate('date_created', $date)->sum('masuk');
            $totalExpense = Keuangan::whereDate('date_created', $date)->sum('keluar');

            $laporan = LaporanKasir::whereDate('mulai', $date)->first();

            if (!$laporan) {
                $laporan = new LaporanKasir();
                $laporan->mulai = $date->copy()->startOfDay();
                $laporan->akhir = $date->copy()->endOfDay();
            }

            $laporan->pemasukkan = $totalIncome;
            $laporan->pengeluaran = $totalExpense;
            $laporan->laba_kotor = $totalIncome - $totalExpense;
            
            // Zero out unused fields for now as we don't have source for them
            $laporan->koreksi_pemasukkan = 0;
            $laporan->koreksi_pengeluaran = 0;

            $laporan->save();
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Report generation completed.');
    }
}

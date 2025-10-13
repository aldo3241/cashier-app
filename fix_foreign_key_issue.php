<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Support\Facades\DB;

echo "Foreign Key Constraint Fix Tool\n";
echo "===============================\n\n";

// Get the sale ID from command line argument or ask user
$saleId = $argv[1] ?? null;

if (!$saleId) {
    echo "Usage: php fix_foreign_key_issue.php [sale_id]\n";
    echo "Example: php fix_foreign_key_issue.php 1760165302\n\n";
    
    echo "Available sales with details:\n";
    $sales = Penjualan::with('penjualanDetails')->has('penjualanDetails')->get(['kd_penjualan', 'no_faktur_penjualan', 'status_bayar']);
    
    foreach ($sales as $sale) {
        echo "ID: {$sale->kd_penjualan} | Invoice: {$sale->no_faktur_penjualan} | Status: {$sale->status_bayar} | Details: {$sale->penjualanDetails->count()}\n";
    }
    exit;
}

try {
    $sale = Penjualan::find($saleId);
    
    if (!$sale) {
        echo "Sale with ID {$saleId} not found.\n";
        exit;
    }
    
    echo "Found sale: {$sale->no_faktur_penjualan}\n";
    echo "Status: {$sale->status_bayar}\n";
    echo "Details count: {$sale->penjualanDetails->count()}\n\n";
    
    echo "Deleting sale details first...\n";
    $deletedDetails = $sale->penjualanDetails()->delete();
    echo "Deleted {$deletedDetails} detail records.\n";
    
    echo "Deleting sale...\n";
    $sale->delete();
    echo "Sale deleted successfully!\n";
    
    echo "\nForeign key constraint issue resolved!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

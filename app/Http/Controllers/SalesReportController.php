<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class SalesReportController extends Controller
{
    /**
     * Show my sales report
     */
    public function mySales()
    {
        $user = auth()->user();
        
        // Simulated sales data - replace with real database query when ready
        $sales = $this->generateMockSalesData($user->kd, 'my');
        
        return view('sales.my-sales', compact('sales', 'user'));
    }
    
    /**
     * Show all cashiers sales report
     */
    public function allSales()
    {
        $user = auth()->user();
        
        // Simulated sales data - replace with real database query when ready
        $sales = $this->generateMockSalesData(null, 'all');
        
        return view('sales.all-sales', compact('sales', 'user'));
    }
    
    /**
     * Generate mock sales data for demonstration
     * Replace this with actual database queries when ready
     */
    private function generateMockSalesData($userId = null, $type = 'my')
    {
        $sales = [];
        $count = $type === 'my' ? rand(10, 20) : rand(30, 50);
        
        for ($i = 0; $i < $count; $i++) {
            $date = Carbon::now()->subDays(rand(0, 30));
            $amount = rand(50000, 500000);
            $items = rand(1, 10);
            
            $cashiers = ['John Doe', 'Jane Smith', 'Mike Wilson', 'Sarah Johnson', 'David Lee'];
            $cashier = $type === 'my' ? (auth()->user()->nama ?? auth()->user()->username) : $cashiers[array_rand($cashiers)];
            
            $paymentMethods = ['Cash', 'Debit Card', 'Credit Card', 'E-Wallet'];
            $statuses = ['Completed', 'Completed', 'Completed', 'Refunded'];
            
            $sales[] = [
                'id' => 'TXN' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'datetime' => $date->format('Y-m-d H:i:s'),
                'cashier' => $cashier,
                'items' => $items,
                'amount' => $amount,
                'formatted_amount' => 'Rp ' . number_format($amount, 0, ',', '.'),
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'status' => $statuses[array_rand($statuses)],
                'customer' => rand(0, 1) ? 'Customer ' . rand(1000, 9999) : 'Walk-in'
            ];
        }
        
        // Sort by date descending
        usort($sales, function($a, $b) {
            return strtotime($b['datetime']) - strtotime($a['datetime']);
        });
        
        return collect($sales);
    }
}


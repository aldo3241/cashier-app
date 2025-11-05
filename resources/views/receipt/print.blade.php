<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk - {{ $transaction->no_faktur_penjualan }}</title>
    <style>
        @media print {
            body { margin: 0; padding: 0; }
            .no-print { display: none !important; }
        }
        
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 16px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            background: white;
            color: black;
        }
        
        .receipt {
            max-width: 100%;
            width: 100%;
            margin: 0 auto;
            background: white;
        }
        
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        
        .store-name {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .store-desc {
            font-size: 16px;
            margin-bottom: 5px;
        }
        
        .address {
            font-size: 14px;
            margin-bottom: 5px;
        }
        
        .phone {
            font-size: 14px;
            margin-bottom: 12px;
        }
        
        .transaction-info {
            text-align: center;
            font-size: 14px;
            margin-bottom: 15px;
        }
        
        .divider {
            border-top: 2px solid black;
            margin: 15px 0;
        }
        
        .items {
            margin-bottom: 10px;
        }
        
        .item {
            margin-bottom: 8px;
        }
        
        .item-name {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 4px;
        }
        
        .item-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .item-qty-price {
            font-size: 14px;
        }
        
        .item-total {
            font-weight: bold;
            font-size: 14px;
        }
        
        .total-section {
            margin: 15px 0;
        }
        
        .total-line {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 20px;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 15px;
        }
        
        .payment-info {
            margin-bottom: 10px;
        }
        
        .payment-line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 14px;
        }
        
        .policy {
            text-align: center;
            font-size: 12px;
            margin: 15px 0;
            font-style: italic;
        }
        
        .served-by {
            text-align: center;
            font-size: 12px;
            margin-top: 15px;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            z-index: 1000;
        }
        
        .print-button:hover {
            background: #45a049;
        }
        
        .back-button {
            background: #6B7280;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .back-button:hover {
            background: #4B5563;
        }
    </style>
</head>
<body>
    <div class="no-print" style="position: fixed; top: 20px; right: 20px; z-index: 1000; display: flex; gap: 10px;">
        <button class="print-button" onclick="window.print()">🖨️ Print Struk</button>
        <button class="back-button" onclick="goBack()">← Back</button>
    </div>
    
    <div class="receipt">
        <!-- Header -->
        <div class="header">
            <div class="store-name">Spirito Santo</div>
            <div class="store-desc">Toko Rohani Pertapaan Karmel</div>
            <div class="address">Desa Ngadireso, Tumpang, Kabupaten Malang, Jawa Timur</div>
            <div class="phone">+62812345678</div>
        </div>
        
        <!-- Transaction Info -->
        <div class="transaction-info">
            {{ \Carbon\Carbon::parse($transaction->date_created)->format('Y-m-d H:i:s') }}|{{ $transaction->kd_penjualan }}-{{ $transaction->no_faktur_penjualan }}-PLG {{ $transaction->kd_pelanggan }}
        </div>
        
        <div class="divider"></div>
        
        <!-- Items -->
        <div class="items">
            @foreach($transaction->penjualanDetails as $item)
            <div class="item">
                <div class="item-name">{{ $item->nama_produk }}</div>
                <div class="item-details">
                    <span class="item-qty-price">{{ $item->qty }} x {{ number_format($item->harga_jual, 0, ',', '.') }}</span>
                    <span class="item-total">{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="divider"></div>
        
        <!-- Total -->
        <div class="total-section">
            <div class="total-line">
                <span>Total=</span>
                <span>{{ number_format($transaction->total_harga, 0, ',', '.') }}</span>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="payment-info">
                <div class="payment-line">
                    <span>Lunas</span>
                    <span>{{ $transaction->keuangan_kotak ?? 'Cash' }}</span>
                </div>
            </div>
            
            <div class="policy">
                Barang yang dibeli, tidak dapat dikembalikan
            </div>
            
            <div class="served-by">
                Dilayani oleh {{ $transaction->dibuat_oleh ?? 'System' }}
            </div>
        </div>
    </div>
    
    <script>
        // Auto print when page loads
        window.onload = function() {
            // Small delay to ensure everything is loaded
            setTimeout(function() {
                window.print();
            }, 500);
        };
        
        // Close window after printing (optional)
        window.onafterprint = function() {
            // Uncomment the line below if you want to close the window after printing
            // window.close();
        };
        
        // Back button function
        function goBack() {
            // Check if there's a previous page in history
            if (window.history.length > 1) {
                window.history.back();
            } else {
                // If no history, redirect to My Sales page
                window.location.href = '/sales/my-sales';
            }
        }
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk - {{ $transaction->no_faktur_penjualan }}</title>
    <style>
        @media print {
            @page { margin: 0; }
            body {padding: 0; }
            .no-print { display: none !important; }
        }

        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 16px;
            line-height: 1.4;
            margin: 0;
            padding: 10px; /* Reduced padding */
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
            font-size: 14px; /* Even smaller font */
            font-weight: bold;
            margin-bottom: 2px; /* Reduced margin */
        }

        .store-desc {
            font-size: 12px; /* Even smaller font */
            font-weight: bold; /* Added bold */
            margin-bottom: 2px; /* Reduced margin */
        }

        .address {
            font-size: 12px; /* Even smaller font */
            font-weight: bold; /* Added bold */
            margin-bottom: 2px; /* Reduced margin */
        }

        .phone {
            font-size: 12px; /* Even smaller font */
            font-weight: bold; /* Added bold */
            margin-bottom: 5px; /* Reduced margin */
        }

        .transaction-info {
            text-align: center;
            font-size: 10px; /* Even smaller font */
            font-weight: bold; /* Added bold */
            margin-bottom: 5px; /* Reduced margin */
        }

        .divider {
            border-top: 2px solid black; /* Slightly thinner line */
            margin: 5px 0; /* Reduced margin */
        }

        .items {
            margin-bottom: 5px; /* Reduced margin */
        }

        .item {
            margin-bottom: 2px; /* Reduced margin */
        }

        .item-line-1 {
            display: flex;
            justify-content: space-between;
            font-size: 12px; /* Smaller font */
        }

        .item-name {
            font-weight: normal;
            font-size: 12px; /* Smaller font */
        }

        .item-total {
            font-weight: normal;
            font-size: 12px; /* Smaller font */
            text-align: right;
        }

        .item-line-2 {
            font-size: 10px; /* Smaller font */
            margin-top: 0px; /* Removed margin */
        }

        .total-section {
            margin: 5px 0; /* Reduced margin */
        }

        .total-line {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px; /* Smaller font */
            font-weight: bold;
        }

        .footer {
            margin-top: 5px; /* Reduced margin */
        }

        .payment-info {
            margin-bottom: 5px; /* Reduced margin */
        }

        .payment-line {
            display: flex;
            justify-content: space-between;
            font-size: 12px; /* Smaller font */
        }

        .payment-line-total {
            font-size: 12px; /* Smaller font */
            font-weight: bold;
        }

        .policy {
            text-align: center;
            font-size: 10px; /* Smaller font */
            margin: 5px 0 2px 0; /* Reduced margin */
        }

        .served-by {
            text-align: center;
            font-size: 10px; /* Smaller font */
            margin-top: 2px; /* Reduced margin */
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
                <div class="item-line-1">
                    <span class="item-name">{{ $item->nama_produk }}</span>
                    <span class="item-total">{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="item-line-2">
                    <span>{{ $item->qty }} x {{ number_format($item->harga_jual, 0, ',', '.') }}</span>
                </div>
            </div>
            @endforeach
        </div>

        <div class="divider"></div>

        <!-- Total and Payment -->
        <div class="total-section">
            <div class="payment-info">
                <div class="payment-line">
                    <div style="display: flex; flex-direction: column;">
                        <span>Lunas</span>
                        <span>Tunai</span>
                    </div>
                    <div style="display: flex; flex-direction: column; text-align: right;">
                        <span class="payment-line-total">Total=</span>
                        <span class="payment-line-total">{{ number_format($transaction->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Footer -->
        <div class="footer">
            <div class="policy">
                Barang yang dibeli, tidak dapat dikembalikan
            </div>

            <div class="served-by">
                Dilayani oleh
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

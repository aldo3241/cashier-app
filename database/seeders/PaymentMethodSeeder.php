<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentMethods = [
            [
                'nama_metode_pembayaran' => 'Tunai',
                'jenis_pembayaran' => 'Cash',
                'nomor_rekening' => null,
                'nama_bank' => null,
                'deskripsi' => 'Pembayaran tunai langsung',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'nama_metode_pembayaran' => 'TRF Rek BCA',
                'jenis_pembayaran' => 'Bank Transfer',
                'nomor_rekening' => '1234567890',
                'nama_bank' => 'BCA',
                'deskripsi' => 'Transfer Bank BCA',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'nama_metode_pembayaran' => 'Debit via EDC BCA',
                'jenis_pembayaran' => 'Card',
                'nomor_rekening' => null,
                'nama_bank' => 'BCA',
                'deskripsi' => 'Kartu debit BCA',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'nama_metode_pembayaran' => 'Transfer Mandiri',
                'jenis_pembayaran' => 'Bank Transfer',
                'nomor_rekening' => '0987654321',
                'nama_bank' => 'Mandiri',
                'deskripsi' => 'Transfer Bank Mandiri',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'nama_metode_pembayaran' => 'QRIS',
                'jenis_pembayaran' => 'Digital Wallet',
                'nomor_rekening' => null,
                'nama_bank' => null,
                'deskripsi' => 'Pembayaran QRIS',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];

        foreach ($paymentMethods as $method) {
            DB::table('metode_pembayaran')->insert($method);
        }
    }
}

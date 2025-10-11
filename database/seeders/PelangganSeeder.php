<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if default customer already exists
        $exists = DB::table('pelanggan')->where('kd_pelanggan', '#PLG1')->exists();
        
        if (!$exists) {
            DB::table('pelanggan')->insert([
                'kd_pelanggan' => '#PLG1',
                'panggilan' => 'Pelanggan',
                'nama_lengkap' => '#PLG1',
                'nama_lembaga' => null,
                'telp' => '-',
                'alamat' => 'Walk-in Customer',
                'kecamatan' => null,
                'kotakab' => null,
                'provinsi' => null,
                'negara' => 'Indonesia',
                'kode_pos' => null,
                'catatan' => 'Default walk-in customer for cash transactions',
                'dibuat_oleh' => 'system',
                'date_created' => Carbon::now(),
                'date_updated' => Carbon::now(),
            ]);
            
            $this->command->info('Default customer #PLG1 created successfully!');
        } else {
            $this->command->info('Default customer #PLG1 already exists.');
        }
    }
}


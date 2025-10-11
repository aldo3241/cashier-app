<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "Available tables:\n";
    $tables = DB::select('SHOW TABLES');
    foreach ($tables as $table) {
        foreach ($table as $key => $value) {
            echo $value . ' ';
        }
        echo "\n";
    }

    echo "\nPelanggan table exists: ";
    $pelangganExists = DB::table('information_schema.tables')
        ->where('table_schema', env('DB_DATABASE'))
        ->where('table_name', 'pelanggan')
        ->exists();
    echo $pelangganExists ? "YES" : "NO";
    echo "\n";

    if ($pelangganExists) {
        echo "\nPelanggan count: ";
        $count = DB::table('pelanggan')->count();
        echo $count;
        echo "\n";

        echo "\nPelanggan sample data:\n";
        $sample = DB::table('pelanggan')->first();
        if ($sample) {
            echo "ID: " . $sample->kd_pelanggan . "\n";
            echo "Nama Lengkap: " . $sample->nama_lengkap . "\n";
            echo "Panggilan: " . $sample->panggilan . "\n";
        } else {
            echo "No data in pelanggan table\n";
        }
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

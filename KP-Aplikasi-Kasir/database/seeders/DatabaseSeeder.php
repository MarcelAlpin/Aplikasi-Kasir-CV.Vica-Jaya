<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@mail.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'kasir@mail.com'],
            [
                'name' => 'Kasir',
                'password' => Hash::make('kasir123'),
                'role' => 'kasir',
            ]
        );

        // Eksekusi file SQL dummy
        // DB::unprepared(file_get_contents(database_path('sql/kategori_dummy.sql')));
        // DB::unprepared(file_get_contents(database_path('sql/satuan_dummy.sql')));
        // DB::unprepared(file_get_contents(database_path('sql/agen_dummy.sql')));
    }
}

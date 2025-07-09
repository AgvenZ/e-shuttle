<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Halte;
use App\Models\Kerumunan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin Account
        User::updateOrCreate(
            ['email' => 'admin@eshuttle.com'],
            [
                'name' => 'Admin E-Shuttle',
                'password' => Hash::make('admin123'),
                'role' => 'admin'
            ]
        );

        // Create User Account
        User::updateOrCreate(
            ['email' => 'user@eshuttle.com'],
            [
                'name' => 'User E-Shuttle',
                'password' => Hash::make('user123'),
                'role' => 'user'
            ]
        );

        // Create Halte Data
        Halte::updateOrCreate(
            ['nama_halte' => 'Halte Terminal Purabaya'],
            [
                'cctv' => 'http://192.168.1.100:8080/video',
                'latitude' => -7.2575,
                'longitude' => 112.7521
            ]
        );

        Halte::updateOrCreate(
            ['nama_halte' => 'Halte Stasiun Gubeng'],
            [
                'cctv' => 'http://192.168.1.101:8080/video',
                'latitude' => -7.2652,
                'longitude' => 112.7519
            ]
        );

        Halte::updateOrCreate(
            ['nama_halte' => 'Halte Tunjungan Plaza'],
            [
                'cctv' => null,
                'latitude' => -7.2636,
                'longitude' => 112.7381
            ]
        );

        // Create Kerumunan Data only if table is empty
        if (Kerumunan::count() == 0) {
            $now = Carbon::now();
            
            // Data kerumunan hari ini
            for ($i = 0; $i < 15; $i++) {
                Kerumunan::create([
                    'id_halte' => rand(1, 3),
                    'waktu' => $now->copy()->subHours(rand(0, 23))->subMinutes(rand(0, 59)),
                    'jumlah_kerumunan' => rand(5, 50)
                ]);
            }

            // Data kerumunan kemarin
            for ($i = 0; $i < 10; $i++) {
                Kerumunan::create([
                    'id_halte' => rand(1, 3),
                    'waktu' => $now->copy()->subDay()->subHours(rand(0, 23))->subMinutes(rand(0, 59)),
                    'jumlah_kerumunan' => rand(3, 45)
                ]);
            }

            // Data kerumunan minggu lalu
            for ($i = 0; $i < 20; $i++) {
                Kerumunan::create([
                    'id_halte' => rand(1, 3),
                    'waktu' => $now->copy()->subDays(rand(2, 7))->subHours(rand(0, 23))->subMinutes(rand(0, 59)),
                    'jumlah_kerumunan' => rand(1, 40)
                ]);
            }
        }
    
    }
}

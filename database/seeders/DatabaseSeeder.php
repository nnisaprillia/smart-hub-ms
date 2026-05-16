<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Room;
use App\Models\Equipment;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // =========================
        // USERS
        // =========================

        // Admin
        User::factory(2)->create([
            'role' => 'admin',
        ]);

        // Member
        User::factory(18)->create([
            'role' => 'member',
        ]);


        // =========================
        // ROOMS
        // =========================
        Room::factory(20)->create();


        // =========================
        // EQUIPMENT
        // =========================
        Equipment::factory(20)->create();
    }
}
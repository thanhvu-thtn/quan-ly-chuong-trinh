<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Gọi UserSeeder chạy
        $this->call([
            UserSeeder::class,
        ]);
        // Thêm dòng này để gọi Seeder Vật lý
        $this->call([
            PhysicsCurriculumSeeder::class,
        ]);
    }
}

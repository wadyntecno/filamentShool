<?php

namespace Database\Seeders;

use App\Models\Standard;
use App\Models\Student;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'w',
        //     'email' => 'w@w.com',
        //     'password' => bcrypt('cris9876'),
        // ]);

        // Student::factory(10)->create();

        $data = collect();

        for ($i = 1; $i <= 10; $i++) {
            $data->push([
                "name" => "Std {$i}",
                'class_number' => $i,
            ]);
        }

        Standard::factory()->createMany($data);

        $this->call(StandardSeeder::class);
    }
}

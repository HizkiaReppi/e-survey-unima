<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use App\Models\Category;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'id' => Str::uuid(),
            'name' => env('SUPER_ADMIN_NAME'),
            'username' => env('SUPER_ADMIN_USERNAME'),
            'email' => env('SUPER_ADMIN_EMAIL'),
            'role' => 'super-admin',
            'password' => bcrypt(env('SUPER_ADMIN_PASSWORD')),
        ]);

        User::factory()->create([
            'id' => Str::uuid(),
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
            'password' => bcrypt('admin'),
        ]);

        $year = Carbon::now()->format('Y');
        for ($i = 1; $i <= 10; $i++) {
            $gender = rand(0, 1);
            $nim = substr($year, -2) . sprintf('%03d', $i);
            $uuid = Str::uuid();
            $user = User::create([
                'id' => $uuid,
                'name' => Factory::create()->name($gender == 1 ? 'male' : 'female'),
                'email' => $nim . '@unima.ac.id',
                'username' => $nim,
                'password' => bcrypt($nim),
                'role' => 'student',
            ]);

            Student::create([
                'id' => Factory::create()->uuid(),
                'user_id' => $uuid,
                'nim' => $nim,
                'batch' => rand(2018, 2024),
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB::table('users')->truncate(); // Opcional

        $adminRole = Role::where('name', 'administrator')->first();

        if ($adminRole) {
            User::create([
                'username' => 'admin',
                'name' => 'Admin',
                'surname' => 'Administrador',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin'),
                'role_id' => $adminRole->id,
                'email_verified_at' => now(),
                'status' => 'active',
            ]);
        }

    }
}

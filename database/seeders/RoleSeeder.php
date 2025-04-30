<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB::table('roles')->truncate();
        Role::create(['name' => 'administrator', 'display_name' => 'Administrador']);
        Role::create(['name' => 'editor', 'display_name' => 'Editor']);
        Role::create(['name' => 'subscriber', 'display_name' => 'Suscriptor']);
    }
}

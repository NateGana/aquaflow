<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class AccessSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::firstOrCreate(['name' => 'admin'], ['label' => 'Administrator']);
        $member = Role::firstOrCreate(['name' => 'member'], ['label' => 'Member']);

        User::updateOrCreate(['email' => 'admin@aquaflow.test'], [
            'name' => 'Aqua Admin',
            'password' => 'password',
            'role_id' => $admin->id,
            'swimming_level' => 'advanced',
            'is_active' => true,
        ]);

        User::updateOrCreate(['email' => 'swimmer@aquaflow.test'], [
            'name' => 'Maya Swimmer',
            'password' => 'password',
            'role_id' => $member->id,
            'swimming_level' => 'beginner',
            'is_active' => true,
        ]);
    }
}

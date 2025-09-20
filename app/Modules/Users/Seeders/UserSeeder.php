<?php

namespace App\Modules\Users\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
  public function run(): void
  {
    // Create Admin User
    User::create([
      'name' => 'Admin User',
      'email' => 'admin@example.com',
      'password' => Hash::make('password'),
      'role' => 'admin',
      'email_verified_at' => now(),
    ]);

    // Create Customer User
    User::create([
      'name' => 'Cliente User',
      'email' => 'cliente@example.com',
      'password' => Hash::make('password'),
      'role' => 'customer',
      'email_verified_at' => now(),
    ]);
  }
}

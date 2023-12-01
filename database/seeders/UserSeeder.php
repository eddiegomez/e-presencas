<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $password = "admin12345";

    \App\Models\User::factory()->create([
      'name' => 'admin',
      'email' => 'admin@admin.com',
      'phone' => '+258821234567',
      'password' => Hash::make($password),
    ]);
  }
}

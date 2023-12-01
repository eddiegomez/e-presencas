<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('organization')->insert([
      "name" => "INAGE",
      "email" => "inage.teste1@inage.gov.mz",
      "phone" => "+258876860612",
      "location" => "Vladimir Lenine",
      "website" => "https://inage.gov.mz/"
    ]);
  }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParticipantTypeSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */

  public function run()
  {
    $data = [
      'Convidado',
      'Orador'
    ];

    foreach ($data as $type) {
      DB::table('participant_type')->insert([
        'name' => $type
      ]);
    }
  }
}

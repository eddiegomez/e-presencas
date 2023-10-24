<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTypeParticipantEventTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('participant_event', function (Blueprint $table) {
      $table->string('qr_url')->unique();
      $table->foreignId('participant_type_id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('participant_event', function (Blueprint $table) {
      //
    });
  }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusParticipantEventTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('participant_event', function (Blueprint $table) {
      $table->enum('status', ['Em espera', 'Confirmada', 'Rejeitada', 'Presente', 'Participou', 'Ausente'])->default('Em espera');
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

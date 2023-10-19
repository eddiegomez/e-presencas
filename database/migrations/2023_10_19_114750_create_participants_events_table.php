<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipantsEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up()
  {
    Schema::create('participant_event', function (Blueprint $table) {
      $table->primary(['participant_id', 'event_id']);
      $table->foreignId('participant_id')->constrained();
      $table->foreignId('event_id')->constrained();
    });
  }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('participant_event');
    }
}

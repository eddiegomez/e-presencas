<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up()
  {
    Schema::create('event_schedule', function (Blueprint $table) {
      $table->primary(['event_id', 'schedule_id']);
      $table->foreignId('event_id')->constrained();
      $table->foreignId('schedule_id')->constrained();
    });
  }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_schedule');
    }
}

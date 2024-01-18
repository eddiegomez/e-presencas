<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduleTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('schedule', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('pdf_url');
      $table->date('date');
      $table->foreignId('event_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('schedule');
  }
}

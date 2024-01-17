<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('events', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('banner_url');
      $table->date('start_date');
      $table->date('end_date');
      $table->time('start_time');
      $table->time('end_time');
      $table->unsignedBigInteger('organization_id');
      $table->foreign('organization_id')->references('id')->on('organization');
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
    Schema::dropIfExists('event');
  }
}

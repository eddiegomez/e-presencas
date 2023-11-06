<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventAddressTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('event_address', function (Blueprint $table) {
      $table->primary(['event_id', 'address_id']);
      $table->foreignId('event_id')->constrained();
      $table->unsignedBigInteger('address_id');
      $table->foreign('address_id')->references('id')->on('address');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('event_address');
  }
}

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
      $table->foreignId('event_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
      $table->unsignedBigInteger('address_id');
      $table->foreign('address_id')->references('id')->on('address')->cascadeOnDelete()->cascadeOnUpdate();
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
    Schema::dropIfExists('event_address');
  }
}

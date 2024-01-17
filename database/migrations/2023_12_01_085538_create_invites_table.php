<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvitesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('invites', function (Blueprint $table) {
      $table->primary(['participant_id', 'event_id']);
      $table->foreignId('participant_id')->constrained();
      $table->foreignId('event_id')->constrained();
      $table->string('qr_url')->unique();
      $table->unsignedBigInteger('participant_type_id');
      $table->foreign('participant_type_id')->references('id')->on('participant_type');
      $table->enum('status', ['Em espera', 'Confirmada', 'Rejeitada', 'Presente', 'Participou', 'Ausente'])->default('Em espera');
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
    Schema::dropIfExists('invites');
  }
}

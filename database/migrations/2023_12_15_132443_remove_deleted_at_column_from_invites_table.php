<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveDeletedAtColumnFromInvitesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('invites', function (Blueprint $table) {
      $table->dropSoftDeletes();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('invites', function (Blueprint $table) {
      // Uncomment the following lines to add the deleted_at column back in case of rollback
      Schema::table('events', function (Blueprint $table) {
        $table->softDeletes();
      });
    });
  }
}

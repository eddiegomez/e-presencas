<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffEventoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_evento', function (Blueprint $table) {
            $table->id(); // Optional: Add an ID column if needed
            $table->unsignedBigInteger('staff_id');
            $table->unsignedBigInteger('evento_id');
            $table->timestamps();

            // Optional: Add foreign key constraints
            $table->foreign('staff_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('evento_id')->references('id')->on('events')->onDelete('cascade');

            // Add a unique constraint if needed
            $table->unique(['staff_id', 'evento_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff_evento');
    }
}

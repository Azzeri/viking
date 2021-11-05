<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
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

            $table->string('name', 64);
            $table->string('description', 255);

            $table->string('addrStreet', 64);
            $table->string('addrNumber', 10);
            $table->string('addrHouseNumber', 10)->nullable();
            $table->string('addrPostcode', 32);
            $table->string('addrTown', 64);

            $table->date('date_start');
            $table->date('date_end');
            $table->time('time_start');
            $table->time('time_end')->nullable();

            $table->boolean('is_finished')->default('false');
            $table->json('participants')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}

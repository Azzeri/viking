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

            $table->string('name', 128)->unique();
            $table->string('description', 512);
            $table->string('description_summary', 512)->nullable();

            $table->string('addrStreet', 64);
            $table->string('addrNumber', 10);
            $table->string('addrPostCode', 10);
            $table->string('addrTown', 64);

            $table->date('date_start');
            $table->date('date_end');
            $table->time('time_start');
            $table->time('time_end')->nullable();

            $table->boolean('is_finished')->default('false');
            $table->string('photo_path')->default('/images/default.png');
            $table->json('participants')->nullable();
            // $table->json('items')->nullable();

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
        Schema::dropIfExists('events');
    }
}

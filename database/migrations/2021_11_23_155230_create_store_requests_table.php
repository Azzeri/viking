<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_requests', function (Blueprint $table) {
            $table->id();
            $table->text('description', 255)->nullable();
            $table->text('note', 255)->nullable();
            $table->date('date_finished')->nullable();
            $table->string('client_name', 64);
            $table->string('client_phone', 64)->nullable();
            $table->string('client_email', 64);
            $table->boolean('is_accepted')->default(false);
            $table->boolean('is_finished')->default(false);
            $table->foreignId('store_item_id')->constrained();
            $table->softDeletes();
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
        Schema::dropIfExists('store_requests');
    }
}

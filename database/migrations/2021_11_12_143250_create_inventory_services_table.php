<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_services', function (Blueprint $table) {
            $table->id();
            $table->string('name', 64);
            $table->text('description')->nullable();
            $table->date('date_due')->nullable();
            $table->date('date_performed')->nullable();
            $table->boolean('notification')->default(false);
            $table->boolean('is_finished')->default(false);
            $table->foreignId('inventory_item_id')->constrained();
            $table->foreignId('assigned_user')->nullable()->constrained('users');
            $table->foreignId('performed_by')->nullable()->constrained('users');
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
        Schema::dropIfExists('inventory_services');
    }
}

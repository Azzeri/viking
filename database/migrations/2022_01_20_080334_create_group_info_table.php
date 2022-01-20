<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_info', function (Blueprint $table) {
            $table->string('short_name', 15);
            $table->string('full_name', 32);
            $table->string('motto', 32);
            $table->text('description', 500);

            $table->string('addr_street', 64);
            $table->string('addr_number', 10);
            $table->string('addr_postCode', 10);
            $table->string('addr_town', 64);

            $table->string('phone', 15);
            $table->string('email', 32);

            $table->string('facebook', 128)->nullable();
            $table->string('youtube', 128)->nullable();
            $table->string('twitter', 128)->nullable();
            $table->string('instagram', 128)->nullable();
            $table->string('tiktok', 128)->nullable();

            $table->string('link1_label', 32)->nullable();
            $table->string('link1_url', 128)->nullable();
            $table->string('link2_label', 32)->nullable();
            $table->string('link2_url', 128)->nullable();
            $table->string('link3_label', 32)->nullable();
            $table->string('link3_url', 128)->nullable();
            $table->string('link4_label', 32)->nullable();
            $table->string('link4_url', 128)->nullable();
            $table->string('link5_label', 32)->nullable();
            $table->string('link5_url', 128)->nullable();
            $table->string('link6_label', 32)->nullable();
            $table->string('link6_url', 128)->nullable();

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
        Schema::dropIfExists('group_info');
    }
}

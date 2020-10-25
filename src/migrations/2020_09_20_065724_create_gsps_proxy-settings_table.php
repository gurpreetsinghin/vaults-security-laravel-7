<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGspsProxySettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gsps_proxy-settings', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('protection')->default(0);
            $table->tinyInteger('protection2')->default(0);
            $table->tinyInteger('protection3')->default(0);
            $table->string('api1')->nullable();
            $table->string('api2')->nullable();
            $table->string('api3')->nullable();
            $table->tinyInteger('logging')->default(1);
            $table->tinyInteger('autoban')->default(0);
            $table->string('redirect')->nullable();
            $table->tinyInteger('mail')->default(0);
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
        Schema::dropIfExists('gsps_proxy-settings');
    }
}

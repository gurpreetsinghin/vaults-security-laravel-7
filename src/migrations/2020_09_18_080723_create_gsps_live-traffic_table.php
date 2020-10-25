<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGspsLiveTrafficTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gsps_live-traffic', function (Blueprint $table) {
            $table->id();
            $table->char('ip', 15)->nullable();
            $table->string('useragent')->nullable();
            $table->string('browser')->nullable();
            $table->string('browser_code')->nullable();
            $table->string('os')->nullable();
            $table->string('os_code')->nullable();
            $table->string('device_type')->nullable();
            $table->string('country')->nullable();
            $table->char('country_code', 2)->default('XX');
            $table->string('request_uri')->nullable();
            $table->string('referer')->nullable();
            $table->tinyInteger('bot')->default(0);
            $table->string('date')->nullable();
            $table->char('time', 5)->nullable();
            $table->tinyInteger('uniquev')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gsps_live-traffic');
    }
}

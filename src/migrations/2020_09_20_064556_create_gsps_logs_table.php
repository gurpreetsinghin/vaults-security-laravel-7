<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGspsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gsps_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ip')->nullable();
            $table->string('date')->nullable();
            $table->string('time')->nullable();
            $table->string('page')->nullable();
            $table->text('query')->nullable();
            $table->string('type')->nullable();
            $table->string('browser')->default('Unknown');
            $table->string('browser_code')->nullable();
            $table->string('os')->default('Unknown');
            $table->string('os_code')->nullable();
            $table->string('country')->default('Unknown');
            $table->string('country_code')->default('XX');
            $table->string('region')->default('Unknown');
            $table->string('city')->default('Unknown');
            $table->double('latitude')->default(0);
            $table->double('longitude')->default(0);
            $table->string('isp')->default('Unknown');
            $table->text('useragent')->nullable();
            $table->string('referer_url')->nullable();

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
        Schema::dropIfExists('gsps_logs');
    }
}

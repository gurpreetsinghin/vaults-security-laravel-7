<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGspsAdblockerSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gsps_adblocker-settings', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('detection')->default(0);
            $table->string('redirect')->default('pages/adblocker-detected.php');
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
        Schema::dropIfExists('gsps_adblocker-settings');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGspsBadbotSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gsps_badbot-settings', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('protection')->default(1);
            $table->tinyInteger('protection2')->default(1);
            $table->tinyInteger('protection3')->default(1);
            $table->tinyInteger('logging')->default(1);
            $table->tinyInteger('autoban')->default(0);
            $table->tinyInteger('mail')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gsps_badbot-settings');
    }
}

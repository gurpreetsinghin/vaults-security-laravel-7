<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGspsSpamSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gsps_spam-settings', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('protection')->default(0);
            $table->tinyInteger('logging')->default(1);
            $table->string('redirect')->default('');
            $table->tinyInteger('autoban')->default(0);
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
        Schema::dropIfExists('gsps_spam-settings');
    }
}

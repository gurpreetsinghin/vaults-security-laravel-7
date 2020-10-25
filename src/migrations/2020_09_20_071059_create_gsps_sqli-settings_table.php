<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGspsSqliSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gsps_sqli-settings', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('protection')->default(1);
            $table->tinyInteger('protection2')->default(1);
            $table->tinyInteger('protection3')->default(1);
            $table->tinyInteger('protection4')->default(1);
            $table->tinyInteger('protection5')->default(0);
            $table->tinyInteger('protection6')->default(1);
            $table->tinyInteger('protection7')->default(0);
            $table->tinyInteger('protection8')->default(0);
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
        Schema::dropIfExists('gsps_sqli-settings');
    }
}

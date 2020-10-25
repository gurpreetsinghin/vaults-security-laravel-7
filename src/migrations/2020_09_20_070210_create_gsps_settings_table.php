<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGspsSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gsps_settings', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable();
            $table->string('username')->default('admin');
            $table->string('password')->nullable();
            $table->tinyInteger('project_security')->default(1);
            $table->tinyInteger('mail_notifications')->default(1);
            $table->integer('ip_detection')->default(0);
            $table->tinyInteger('countryban_blacklist')->default(1);
            $table->tinyInteger('live_traffic')->default(0);
            $table->string('badword_replace')->default('[Censored]');
            $table->integer('error_reporting')->default(5);
            $table->integer('display_errors')->default(0);
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
        Schema::dropIfExists('gsps_settings');
    }
}

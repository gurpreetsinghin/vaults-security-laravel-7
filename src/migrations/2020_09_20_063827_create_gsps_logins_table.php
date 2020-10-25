<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGspsLoginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gsps_logins', function (Blueprint $table) {
            $table->id();
            $table->string('username')->nullable();
            $table->char('ip', 15)->nullable();
            $table->string('date')->nullable();
            $table->char('time', 5)->nullable();
            $table->tinyInteger('successful')->nullable();
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
        Schema::dropIfExists('gsps_logins');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNominatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nominators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('month_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('discord_id', 20);
            $table->timestamps();

            $table->unique(['month_id', 'discord_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nominators');
    }
}

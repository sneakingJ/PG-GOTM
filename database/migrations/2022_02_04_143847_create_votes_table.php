<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('month_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->string('discord_id', 20);
            $table->boolean('short')->nullable();
            $table->foreignId('rank_1')->constrained('nominations')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('rank_2')->constrained('nominations')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('rank_3')->constrained('nominations')->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('votes');
    }
}

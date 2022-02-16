<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWinnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('winners', function (Blueprint $table) {
            $table->string('game_id', 20);
            $table->foreignId('month_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('nomination_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->boolean('short')->nullable();
            $table->string('game_name');
            $table->string('game_year')->nullable();
            $table->string('game_cover')->nullable();
            $table->string('game_url')->nullable();
            $table->string('game_platform_ids')->nullable();
            $table->timestamps();

            $table->primary('game_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('winners');
    }
}

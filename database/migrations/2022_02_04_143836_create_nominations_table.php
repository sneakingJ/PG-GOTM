<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNominationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nominations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('month_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->string('game_id', 20);
            $table->string('discord_id', 20);
            $table->boolean('short')->nullable();
            $table->string('game_name');
            $table->string('game_year')->nullable();
            $table->string('game_cover')->nullable();
            $table->string('game_url')->nullable();
            $table->string('game_platform_ids')->nullable();
            $table->text('pitch')->nullable();
            $table->boolean('jury_selected')->default(false);
            $table->timestamps();

            $table->unique(['month_id', 'game_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nominations');
    }
}

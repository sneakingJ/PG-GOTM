<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRankingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rankings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vote_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('nomination_id')->constrained('nominations')->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('rank');
            $table->timestamps();

            $table->unique(['vote_id', 'rank']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rankings');
    }
}

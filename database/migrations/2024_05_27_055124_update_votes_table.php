<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->dropForeign(['rank_1']);
            $table->dropForeign(['rank_2']);
            $table->dropForeign(['rank_3']);
            $table->dropColumn(['rank_1', 'rank_2', 'rank_3']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->foreignId('rank_1')->constrained('nominations')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('rank_2')->constrained('nominations')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('rank_3')->constrained('nominations')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }
}

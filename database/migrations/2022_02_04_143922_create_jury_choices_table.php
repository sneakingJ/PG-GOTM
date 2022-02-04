<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJuryChoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jury_choices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nomination_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('jury_member_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('jury_state_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->tinyInteger('priority');
            $table->text('comment')->nullable();
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
        Schema::dropIfExists('jury_choices');
    }
}

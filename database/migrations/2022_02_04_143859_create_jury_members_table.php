<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJuryMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jury_members', function (Blueprint $table) {
            $table->id();
            $table->string('discord_id', 20);
            $table->string('name');
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->unique('discord_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jury_members');
    }
}

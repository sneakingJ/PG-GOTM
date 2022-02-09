<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIgdbPlatformsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('igdb_platforms', function (Blueprint $table) {
            $table->unsignedBigInteger('igdb_id');
            $table->string('name')->nullable();
            $table->string('logo')->nullable();
            $table->timestamps();

            $table->primary('igdb_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('igdb_platforms');
    }
}

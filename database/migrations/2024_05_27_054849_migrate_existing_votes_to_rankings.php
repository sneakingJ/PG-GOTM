<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MigrateExistingVotesToRankings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('rankings')) {
            $votes = DB::table('votes')->get();

            foreach ($votes as $vote) {
                if ($vote->rank_1) {
                    DB::table('rankings')->insert([
                        'vote_id' => $vote->id,
                        'nomination_id' => $vote->rank_1,
                        'rank' => 1,
                        'created_at' => $vote->created_at,
                        'updated_at' => $vote->updated_at
                    ]);
                }

                if ($vote->rank_2) {
                    DB::table('rankings')->insert([
                        'vote_id' => $vote->id,
                        'nomination_id' => $vote->rank_2,
                        'rank' => 2,
                        'created_at' => $vote->created_at,
                        'updated_at' => $vote->updated_at
                    ]);
                }

                if ($vote->rank_3) {
                    DB::table('rankings')->insert([
                        'vote_id' => $vote->id,
                        'nomination_id' => $vote->rank_3,
                        'rank' => 3,
                        'created_at' => $vote->created_at,
                        'updated_at' => $vote->updated_at
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('rankings')) {
            DB::table('rankings')->truncate();
        }
    }
}

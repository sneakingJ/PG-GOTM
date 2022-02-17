<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JuryMember;

class JuryMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (JuryMember::all()->count() > 0) {
            echo "Jury_Members is not empty\n";
            return;
        }

        $juryMembers = array();

        foreach ($juryMembers as $juryMember) {
            JuryMember::create($juryMember);
        }
    }
}

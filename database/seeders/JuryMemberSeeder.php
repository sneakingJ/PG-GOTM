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

        $juryMembers = array(
            [
                'discord_id' => '603922673302765597',
                'name' => 'Dunwell',
                'active' => true
            ],
            [
                'discord_id' => '234682909334831115',
                'name' => 'GM_Yahya',
                'active' => true
            ],
            [
                'discord_id' => '180059713457618945',
                'name' => 'Joas',
                'active' => true
            ],
            [
                'discord_id' => '102231135320297472',
                'name' => 'K-ralz',
                'active' => true
            ],
            [
                'discord_id' => '219680347510669312',
                'name' => 'Katare',
                'active' => true
            ],
            [
                'discord_id' => '167002616671240192',
                'name' => 'Prah',
                'active' => true
            ],
            [
                'discord_id' => '215872779587682304',
                'name' => 'Ranja',
                'active' => true
            ],
            [
                'discord_id' => '199024346465828864',
                'name' => 'Pumkin',
                'active' => true
            ],
            [
                'discord_id' => '534239829949546506',
                'name' => 'possessedcow',
                'active' => true
            ],
            [
                'discord_id' => '196012129223049217',
                'name' => 'pudding',
                'active' => true
            ],
            [
                'discord_id' => '430324009331326987',
                'name' => 'Shane',
                'active' => true
            ],
        );

        foreach ($juryMembers as $juryMember) {
            JuryMember::create($juryMember);
        }
    }
}

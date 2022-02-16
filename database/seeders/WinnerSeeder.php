<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Winner;
use Illuminate\Support\Str;
use MarcReichel\IGDBLaravel\Models\Game;

class WinnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Winner::all()->count() > 0) {
            echo "Winners is not empty\n";
            return;
        }

        $winnerEntries = array(
            ['id' => '1879', 'short' => false, 'month_id' => 1], // Terraria
            ['id' => '7767', 'short' => true, 'month_id' => 1], // Mini Metro
            ['id' => '73', 'short' => false, 'month_id' => 2], // Mass Effect 1
            ['id' => '578', 'short' => true, 'month_id' => 2], // Warhammer 40,000: Space Marine
            ['id' => '7706', 'short' => false, 'month_id' => 3], // This War of Mine
            ['id' => '2935', 'short' => true, 'month_id' => 3], // Papers, Please
            ['id' => '5601', 'short' => false, 'month_id' => 4], // The Witness
            ['id' => '71', 'short' => true, 'month_id' => 4], // Portal
            ['id' => '181', 'short' => false, 'month_id' => 5], // Grim Fandango
            ['id' => '60', 'short' => true, 'month_id' => 5], // The Secret of Monkey Island
            ['id' => '1028', 'short' => false, 'month_id' => 6], // The Legend of Zelda: Link's Awakening
            ['id' => '1128', 'short' => true, 'month_id' => 6], // Castlevania: Symphony of the Night
            ['id' => '3075', 'short' => false, 'month_id' => 7], // FTL
            ['id' => '7886', 'short' => true, 'month_id' => 7], // Crypt of the Necrodancer
            ['id' => '500', 'short' => false, 'month_id' => 8], // Batman Arkham Asylum
            ['id' => '3022', 'short' => true, 'month_id' => 8], // Transistor
            ['id' => '49', 'short' => false, 'month_id' => 9], // Syndicate
            ['id' => '5378', 'short' => true, 'month_id' => 9], // Ecco the Dolphin
            ['id' => '1802', 'short' => false, 'month_id' => 10], // Chrono Trigger
            ['id' => '4756', 'short' => true, 'month_id' => 10], // Child of Light
            ['id' => '17000', 'short' => false, 'month_id' => 11], // Stardew Valley
            ['id' => '5025', 'short' => true, 'month_id' => 11], // To the Moon
            ['id' => '1341', 'short' => false, 'month_id' => 12], // Beyond Good & Evil
            ['id' => '836', 'short' => true, 'month_id' => 12], // Prince of Persia: Sands of Time
            ['id' => '9928', 'short' => null, 'month_id' => 13], // Dirt Rally
            ['id' => '9254', 'short' => false, 'month_id' => 14], // Subnautica
            ['id' => '9727', 'short' => true, 'month_id' => 14], // SOMA
            ['id' => '76', 'short' => false, 'month_id' => 15], // Dragon Age: Origins
            ['id' => '212', 'short' => true, 'month_id' => 15], // Brutal Legend
            ['id' => '76885', 'short' => false, 'month_id' => 16], // Soul Calibur VI
            ['id' => '11198', 'short' => true, 'month_id' => 16], // Rocket League
            ['id' => '11182', 'short' => false, 'month_id' => 17], // Enter The Gungeon
            ['id' => '17026', 'short' => true, 'month_id' => 17], // Furi
            ['id' => '233', 'short' => false, 'month_id' => 18], // Half Life 2
            ['id' => '2207', 'short' => true, 'month_id' => 18], // Shadow of the Colossus
            ['id' => '20', 'short' => false, 'month_id' => 19], // Bioshock
            ['id' => '17447', 'short' => true, 'month_id' => 19], // Titanfall 2
            ['id' => '3968', 'short' => false, 'month_id' => 20], // Dragons Dogma
            ['id' => '521', 'short' => true, 'month_id' => 20], // Fable
            ['id' => '427', 'short' => false, 'month_id' => 21], // Final Fantasy 7
            ['id' => '7650', 'short' => true, 'month_id' => 21], // The Last Express
            ['id' => '981', 'short' => false, 'month_id' => 22], // Rayman Origins
            ['id' => '1991', 'short' => true, 'month_id' => 22], // FEZ
            ['id' => '364', 'short' => false, 'month_id' => 23], // Heroes of Might and Magic III
            ['id' => '27117', 'short' => true, 'month_id' => 23], // Into The Breach
            ['id' => '7386', 'short' => false, 'month_id' => 24], // The Talos Principle
            ['id' => '11346', 'short' => true, 'month_id' => 24], // Her Story
            ['id' => '1339', 'short' => false, 'month_id' => 25], // Psychonauts
            ['id' => '1051', 'short' => true, 'month_id' => 25], // Mirrors Edge
            ['id' => '327', 'short' => false, 'month_id' => 26], // Age of Empires 2
            ['id' => '749', 'short' => true, 'month_id' => 26], // Homeworld
            ['id' => '2261', 'short' => false, 'month_id' => 27], // Gothic
            ['id' => '18', 'short' => true, 'month_id' => 27], // Max Payne
            ['id' => '533', 'short' => false, 'month_id' => 28], // Dishonored
            ['id' => '6044', 'short' => true, 'month_id' => 28], // Invisible Inc.
            ['id' => '14593', 'short' => false, 'month_id' => 29], // Hollow Knight
            ['id' => '9806', 'short' => true, 'month_id' => 29], // Hyper Light Drifter
            ['id' => '41', 'short' => false, 'month_id' => 30], // Deus Ex (2000)
            ['id' => '11233', 'short' => true, 'month_id' => 30], // What Remains of Edith Finch
            ['id' => '9789', 'short' => false, 'month_id' => 31], // RimWorld
            ['id' => '76638', 'short' => true, 'month_id' => 31], // Baba is You
            ['id' => '1267', 'short' => false, 'month_id' => 32], // Sleeping Dogs
            ['id' => '2001', 'short' => true, 'month_id' => 32], // Far Cry 3: Blood Dragon
            ['id' => '114795', 'short' => false, 'month_id' => 33], // Apex Legends
            ['id' => '74545', 'short' => true, 'month_id' => 33], // Opus Magnum
            ['id' => '11737', 'short' => false, 'month_id' => 34], // Outer Wilds
            ['id' => '9643', 'short' => true, 'month_id' => 34], // Return of the Obra Dinn
            ['id' => '2899', 'short' => false, 'month_id' => 35], // Earthbound
            ['id' => '312', 'short' => true, 'month_id' => 35], // Doom 2
            ['id' => '4843', 'short' => false, 'month_id' => 36], // Kingdom Come: Deliverance
            ['id' => '9730', 'short' => true, 'month_id' => 36], // Firewatch
            ['id' => '36926', 'short' => false, 'month_id' => 37], // Monster Hunter: World
            ['id' => '26226', 'short' => true, 'month_id' => 37], // Celeste
            ['id' => '481', 'short' => false, 'month_id' => 38], // Silent Hill 2
            ['id' => '2485', 'short' => true, 'month_id' => 38], // Luigis Mansion
            ['id' => '16', 'short' => false, 'month_id' => 39], // Fallout: New Vegas
            ['id' => '495', 'short' => true, 'month_id' => 39], // Metro 2033
            ['id' => '16287', 'short' => false, 'month_id' => 40], // Slime Rancher
            ['id' => '116753', 'short' => true, 'month_id' => 40], // A Short Hike
            ['id' => '27134', 'short' => false, 'month_id' => 41], // Deep Rock Galactic
            ['id' => '8351', 'short' => true, 'month_id' => 41], // Tabletop Simulator
            ['id' => '26472', 'short' => false, 'month_id' => 42], // Disco Elysium
            ['id' => '14587', 'short' => true, 'month_id' => 42] // Oxenfree
        );

        foreach ($winnerEntries as $winnerEntry) {
            $game = Game::where('id', (int)$winnerEntry['id'])->with(['cover'])->first();

            $winner = new Winner();
            $winner->game_id = $winnerEntry['id'];
            $winner->month_id = $winnerEntry['month_id'];
            $winner->short = $winnerEntry['short'];
            $winner->game_name = $game->name;
            $winner->game_year = Str::substr($game->first_release_date, 0, 4);
            $winner->game_cover = empty($game->cover['url']) ? '' : $game->cover['url'];
            $winner->game_url = $game->url;
            $winner->game_platform_ids = implode(',', $game->platforms);

            $winner->save();
        }
    }
}

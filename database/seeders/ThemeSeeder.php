<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Theme;

class ThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Theme::all()->count() > 0) {
            echo "Themes is not empty\n";
            return;
        }

        $themes = array(
            [
                'id' => 1,
                'theme_category_id' => 1,
                'name' => 'Roguelike / Roguelite',
                'description' => 'Rogue, NetHack, FTL, The Binding of Isaac'
            ],
            [
                'id' => 2,
                'theme_category_id' => 1,
                'name' => 'Sport games / Fighting',
                'description' => 'Street Fighter, Mario Tennis'
            ],
            [
                'id' => 3,
                'theme_category_id' => 1,
                'name' => 'Racing Games',
                'description' => 'Mario Kart, Need for Speed'
            ],
            [
                'id' => 4,
                'theme_category_id' => 1,
                'name' => 'Shoot \'em up / Run and Gun / Top Down Shooter / Twin Stick',
                'description' => 'Gradius, Hotline Miami'
            ],
            [
                'id' => 5,
                'theme_category_id' => 1,
                'name' => '2D Platformer',
                'description' => 'Super Mario Bros., Castlevania'
            ],
            [
                'id' => 6,
                'theme_category_id' => 1,
                'name' => '3D Platformer',
                'description' => 'Banjo-Kazooie, Crash Bandicoot, Spyro'
            ],
            [
                'id' => 7,
                'theme_category_id' => 1,
                'name' => 'Choice-based / Purely story focused games',
                'description' => 'Visual Novels, Telltale(-like) Games, Walking Sims'
            ],
            [
                'id' => 8,
                'theme_category_id' => 1,
                'name' => 'FPS',
                'description' => 'Doom, Half-Life'
            ],
            [
                'id' => 9,
                'theme_category_id' => 1,
                'name' => '3rd Person Shooter',
                'description' => 'Spec Ops, Gears of War'
            ],
            [
                'id' => 10,
                'theme_category_id' => 1,
                'name' => 'Action-Adventure',
                'description' => 'The Legend of Zelda, Tomb Raider, Subnautica'
            ],
            [
                'id' => 11,
                'theme_category_id' => 1,
                'name' => 'Horror',
                'description' => 'Resident Evil, Silent Hill, Amnesia'
            ],
            [
                'id' => 12,
                'theme_category_id' => 1,
                'name' => 'Strategy / Tactic (Turn Based)',
                'description' => 'X-COM, Total War'
            ],
            [
                'id' => 13,
                'theme_category_id' => 1,
                'name' => 'Strategy / Tactic (Real Time)',
                'description' => 'Age of Empires, Anno'
            ],
            [
                'id' => 14,
                'theme_category_id' => 1,
                'name' => 'Action RPG',
                'description' => 'Mass Effect, Skyrim, Diablo'
            ],
            [
                'id' => 15,
                'theme_category_id' => 1,
                'name' => 'Turn Based RPG',
                'description' => 'Dragon Quest, Persona, Pokémon'
            ],
            [
                'id' => 16,
                'theme_category_id' => 1,
                'name' => 'Puzzle',
                'description' => 'Tetris, Portal'
            ],
            [
                'id' => 17,
                'theme_category_id' => 1,
                'name' => 'Building Sim / Simulation',
                'description' => 'SimCity, The Sims, Kerbal Space Program'
            ],
            [
                'id' => 18,
                'theme_category_id' => 1,
                'name' => 'Stealth',
                'description' => 'Thief, Splinter Cell, MGS, Mark of the Ninja'
            ],
            [
                'id' => 19,
                'theme_category_id' => 1,
                'name' => 'Point and Click Adventure',
                'description' => 'Monkey Island, Black Mirror'
            ],
            [
                'id' => 20,
                'theme_category_id' => 1,
                'name' => 'Retro Games',
                'description' => 'Console games up to the 5th generation or computer games pre 1998'
            ],
            [
                'id' => 21,
                'theme_category_id' => 1,
                'name' => 'Random',
                'description' => 'Anything goes. Nominate your hidden gem or obscurest game'
            ],
            [
                'id' => 22,
                'theme_category_id' => 1,
                'name' => 'Metroidvania',
                'description' => 'Ori, Hollow Knight'
            ],
            [
                'id' => 23,
                'theme_category_id' => 2,
                'name' => '1992 / 1993'
            ],
            [
                'id' => 24,
                'theme_category_id' => 2,
                'name' => '1994 / 1995'
            ],
            [
                'id' => 25,
                'theme_category_id' => 2,
                'name' => '1996 / 1997'
            ],
            [
                'id' => 26,
                'theme_category_id' => 2,
                'name' => '1998 / 1999'
            ],
            [
                'id' => 27,
                'theme_category_id' => 2,
                'name' => '2000 / 2001'
            ],
            [
                'id' => 28,
                'theme_category_id' => 2,
                'name' => '2002 / 2003'
            ],
            [
                'id' => 29,
                'theme_category_id' => 2,
                'name' => '2004 / 2005'
            ],
            [
                'id' => 30,
                'theme_category_id' => 2,
                'name' => '2006 / 2007'
            ],
            [
                'id' => 31,
                'theme_category_id' => 2,
                'name' => '2008 / 2009'
            ],
            [
                'id' => 32,
                'theme_category_id' => 2,
                'name' => '2010 / 2011'
            ],
            [
                'id' => 33,
                'theme_category_id' => 2,
                'name' => '2012 / 2013'
            ],
            [
                'id' => 34,
                'theme_category_id' => 2,
                'name' => '2014 / 2015'
            ],
            [
                'id' => 35,
                'theme_category_id' => 2,
                'name' => '2016 / 2017'
            ],
            [
                'id' => 36,
                'theme_category_id' => 2,
                'name' => '2018 / 2019'
            ],
            [
                'id' => 37,
                'theme_category_id' => 2,
                'name' => '2020 / 2021'
            ],
            [
                'id' => 38,
                'theme_category_id' => 3,
                'name' => 'Immersion',
                'description' => 'Playing the game as a way to step into another world. This often takes the form of a power fantasy (eg. Arkham Asylum\'s "becoming Batman"), but some titles distinctly do not (eg Viscera Cleanup Detail\'s "becoming a spaceship Janitor"). Some don\'t involve becoming anything in particular, just immersing yourself somewhere else (eg. Proteus).'
            ],
            [
                'id' => 39,
                'theme_category_id' => 3,
                'name' => 'Narrative',
                'description' => 'Playing the game as a way to experience a story. This can be a predefined story (eg. The Last of Us), or a game/engine that naturally generates interesting stories (eg. Dwarf Fortress, Crusader Kings 2).'
            ],
            [
                'id' => 40,
                'theme_category_id' => 3,
                'name' => 'Mastery',
                'description' => 'Playing the game as a way to experience and triumph over challenges. A game whose completion demands a major buildup of player skill (eg. Celeste) evokes this well, as does a game that encourages and rewards that skill (eg. Hitman)'
            ],
            [
                'id' => 41,
                'theme_category_id' => 3,
                'name' => 'Discovery',
                'description' => 'Playing the game for the joy of seeking and finding unknown things. This can come in the form of hiding away powerful rewards (eg. Hollow Knight) or by creating an environment that feels intrinsically satisfying to explore on its own (eg. Her Story)'
            ],
            [
                'id' => 42,
                'theme_category_id' => 3,
                'name' => 'Collaboration',
                'description' => 'Playing the game as a vector to cooperate and socialize with others. The concept of a dedicated "co-op title" (eg. Overcooked) is already well-understood, fortunately. However, other multiplayer titles with a strong cooperative element (eg. guilds in Final Fantasy 14) could also strongly evoke this theme.'
            ],
            [
                'id' => 43,
                'theme_category_id' => 3,
                'name' => 'Creativity',
                'description' => 'Playing the game as an outlet for expressing yourself. Any game with a heavy building/decoration element fits well here (eg. The Sims, Minecraft), as do titles that encourage you to solve problems in your own unique way (eg. Opus Magnum)'
            ],
            [
                'id' => 44,
                'theme_category_id' => 3,
                'name' => 'Competition',
                'description' => 'Playing the game to triumph over others who also play. This is also very well-understood to mean competitive multiplayer titles (eg. Starcraft, Tekken, Super Smash Bros), but it can also be evoked well by of asynchronous titles with leaderboards (eg. DIRT Rally, PAC-MAN).'
            ],
            [
                'id' => 45,
                'theme_category_id' => 3,
                'name' => 'Decompression',
                'description' => 'Playing the game to chill out, or as a calming habit. Many games feel naturally calming due to a slow pace (eg. Stardew Valley), while others can be easy to pick up and casually play (eg. One Finger Death Punch, Borderlands).'
            ],
            [
                'id' => 46,
                'theme_category_id' => 4,
                'name' => 'Spooktober'
            ],
            [
                'id' => 47,
                'theme_category_id' => 4,
                'name' => 'Second Chance'
            ],
            [
                'id' => 48,
                'theme_category_id' => 5,
                'name' => 'Female/Non-Binary Protagonist'
            ]
        );

        foreach ($themes as $theme) {
            Theme::create($theme);
        }
    }
}

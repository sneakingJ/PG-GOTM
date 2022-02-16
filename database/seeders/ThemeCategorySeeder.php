<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ThemeCategory;

class ThemeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (ThemeCategory::all()->count() > 0) {
            echo "Theme_Categories is not empty\n";
            return;
        }

        $themeCategories = array(
            [
                'id' => 1,
                'name' => 'Original Genres'
            ],
            [
                'id' => 2,
                'name' => 'By Year'
            ],
            [
                'id' => 3,
                'name' => 'Motivations'
            ],
            [
                'id' => 4,
                'name' => 'Annuals'
            ]
        );

        foreach ($themeCategories as $themeCategory) {
            ThemeCategory::create($themeCategory);
        }
    }
}

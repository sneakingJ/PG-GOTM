<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Month;

class MonthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Month::all()->count() > 0) {
            echo "Months is not empty\n";
            return;
        }

        $months = array(
            [
                'id' => 1,
                'theme_id' => 17,
                'year' => 2018,
                'month' => 9,
                'status' => 'over'
            ],
            [
                'id' => 2,
                'theme_id' => 9,
                'year' => 2018,
                'month' => 10,
                'status' => 'over'
            ],
            [
                'id' => 3,
                'theme_id' => 7,
                'year' => 2018,
                'month' => 11,
                'status' => 'over'
            ],
            [
                'id' => 4,
                'theme_id' => 16,
                'year' => 2018,
                'month' => 12,
                'status' => 'over'
            ],
            [
                'id' => 5,
                'theme_id' => 19,
                'year' => 2019,
                'month' => 1,
                'status' => 'over'
            ],
            [
                'id' => 6,
                'theme_id' => 20,
                'year' => 2019,
                'month' => 2,
                'status' => 'over'
            ],
            [
                'id' => 7,
                'theme_id' => 1,
                'year' => 2019,
                'month' => 3,
                'status' => 'over'
            ],
            [
                'id' => 8,
                'theme_id' => 10,
                'year' => 2019,
                'month' => 4,
                'status' => 'over'
            ],
            [
                'id' => 9,
                'theme_id' => 23,
                'year' => 2019,
                'month' => 5,
                'status' => 'over'
            ],
            [
                'id' => 10,
                'theme_id' => 15,
                'year' => 2019,
                'month' => 6,
                'status' => 'over'
            ],
            [
                'id' => 11,
                'theme_id' => 21,
                'year' => 2019,
                'month' => 7,
                'status' => 'over'
            ],
            [
                'id' => 12,
                'theme_id' => 28,
                'year' => 2019,
                'month' => 8,
                'status' => 'over'
            ],
            [
                'id' => 13,
                'theme_id' => 3,
                'year' => 2019,
                'month' => 9,
                'status' => 'over'
            ],
            [
                'id' => 14,
                'theme_id' => 46,
                'year' => 2019,
                'month' => 10,
                'status' => 'over'
            ],
            [
                'id' => 15,
                'theme_id' => 31,
                'year' => 2019,
                'month' => 11,
                'status' => 'over'
            ],
            [
                'id' => 16,
                'theme_id' => 2,
                'year' => 2019,
                'month' => 12,
                'status' => 'over'
            ],
            [
                'id' => 17,
                'theme_id' => 4,
                'year' => 2020,
                'month' => 1,
                'status' => 'over'
            ],
            [
                'id' => 18,
                'theme_id' => 29,
                'year' => 2020,
                'month' => 2,
                'status' => 'over'
            ],
            [
                'id' => 19,
                'theme_id' => 8,
                'year' => 2020,
                'month' => 3,
                'status' => 'over'
            ],
            [
                'id' => 20,
                'theme_id' => 14,
                'year' => 2020,
                'month' => 4,
                'status' => 'over'
            ],
            [
                'id' => 21,
                'theme_id' => 25,
                'year' => 2020,
                'month' => 5,
                'status' => 'over'
            ],
            [
                'id' => 22,
                'theme_id' => 5,
                'year' => 2020,
                'month' => 6,
                'status' => 'over'
            ],
            [
                'id' => 23,
                'theme_id' => 12,
                'year' => 2020,
                'month' => 7,
                'status' => 'over'
            ],
            [
                'id' => 24,
                'theme_id' => 34,
                'year' => 2020,
                'month' => 8,
                'status' => 'over'
            ],
            [
                'id' => 25,
                'theme_id' => 6,
                'year' => 2020,
                'month' => 9,
                'status' => 'over'
            ],
            [
                'id' => 26,
                'theme_id' => 13,
                'year' => 2020,
                'month' => 10,
                'status' => 'over'
            ],
            [
                'id' => 27,
                'theme_id' => 27,
                'year' => 2020,
                'month' => 11,
                'status' => 'over'
            ],
            [
                'id' => 28,
                'theme_id' => 18,
                'year' => 2020,
                'month' => 12,
                'status' => 'over'
            ],
            [
                'id' => 29,
                'theme_id' => 35,
                'year' => 2021,
                'month' => 1,
                'status' => 'over'
            ],
            [
                'id' => 30,
                'theme_id' => 39,
                'year' => 2021,
                'month' => 2,
                'status' => 'over'
            ],
            [
                'id' => 31,
                'theme_id' => 43,
                'year' => 2021,
                'month' => 3,
                'status' => 'over'
            ],
            [
                'id' => 32,
                'theme_id' => 33,
                'year' => 2021,
                'month' => 4,
                'status' => 'over'
            ],
            [
                'id' => 33,
                'theme_id' => 44,
                'year' => 2021,
                'month' => 5,
                'status' => 'over'
            ],
            [
                'id' => 34,
                'theme_id' => 41,
                'year' => 2021,
                'month' => 6,
                'status' => 'over'
            ],
            [
                'id' => 35,
                'theme_id' => 24,
                'year' => 2021,
                'month' => 7,
                'status' => 'over'
            ],
            [
                'id' => 36,
                'theme_id' => 38,
                'year' => 2021,
                'month' => 8,
                'status' => 'over'
            ],
            [
                'id' => 37,
                'theme_id' => 40,
                'year' => 2021,
                'month' => 9,
                'status' => 'over'
            ],
            [
                'id' => 38,
                'theme_id' => 46,
                'year' => 2021,
                'month' => 10,
                'status' => 'over'
            ],
            [
                'id' => 39,
                'theme_id' => 32,
                'year' => 2021,
                'month' => 11,
                'status' => 'over'
            ],
            [
                'id' => 40,
                'theme_id' => 45,
                'year' => 2021,
                'month' => 12,
                'status' => 'over'
            ],
            [
                'id' => 41,
                'theme_id' => 42,
                'year' => 2022,
                'month' => 1,
                'status' => 'over'
            ],
            [
                'id' => 42,
                'theme_id' => 47,
                'year' => 2022,
                'month' => 2,
                'status' => 'over'
            ],
        );

        foreach ($months as $month) {
            Month::create($month);
        }
    }
}

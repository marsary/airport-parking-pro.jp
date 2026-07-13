<?php

namespace Database\Seeders;

use App\Models\SeasonPriceSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeasonPriceSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $officeId = config('const.commons.office_id');
        $special_seasons = [
            '2025-12-24',
            '2025-12-25',
            '2025-12-26',
            '2025-12-27',
            '2025-12-28',
            '2025-12-29',
            '2025-12-30',
            '2025-12-31',
            '2026-01-01',
            '2026-01-02',
            '2026-04-24',
            '2026-04-25',
            '2026-04-26',
            '2026-04-27',
            '2026-04-28',
            '2026-04-29',
            '2026-04-30',
            '2026-05-01',
            '2026-05-02',
            '2026-05-03',
            '2026-07-30',
            '2026-07-31',
            '2026-08-01',
            '2026-08-02',
            '2026-08-03',
            '2026-08-04',
            '2026-08-05',
            '2026-08-17',
            '2026-08-18',
            '2026-08-19',
            '2026-08-20',
            '2026-08-21',
            '2026-08-22',
            '2026-08-23',
            '2026-08-24',
            '2026-08-25',
            '2026-08-26'
        ];

        $special_seasons2 = [
            '2026-08-06',
            '2026-08-07',
            '2026-08-08',
            '2026-08-09',
            '2026-08-10',
            '2026-08-11',
            '2026-08-12',
            '2026-08-13',
            '2026-08-14',
            '2026-08-15',
            '2026-08-16',
            '2026-12-23',
            '2026-12-24',
            '2026-12-25',
            '2026-12-26',
            '2026-12-27',
            '2026-12-28',
            '2026-12-29',
            '2026-12-30',
            '2026-12-31',
            '2027-01-01',
            '2027-01-02'
        ];
        foreach ($special_seasons as $date) {
            SeasonPriceSetting::create([
                'office_id' => $officeId,
                'target_date' => $date,
                'season_price' => 1500, // 特別料金
            ]);
        }
        foreach ($special_seasons2 as $date) {
            SeasonPriceSetting::create([
                'office_id' => $officeId,
                'target_date' => $date,
                'season_price' => 2000, // 特別料金2
            ]);
        }
    }
}

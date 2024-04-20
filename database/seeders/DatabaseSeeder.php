<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            // AirportSeeder::class,
            // OfficeSeeder::class,
            // MemberTypeSeeder::class,
            // UserSeeder::class,
            // MemberSeeder::class,
            // CarMakerSeeder::class,
            // CarColorSeeder::class,
            // CarSeeder::class,
            // CarCautionSeeder::class,
            // AgencySeeder::class,
            // AirlineSeeder::class,
            // AirportTerminalSeeder::class,
            // LabelSeeder::class,
            // TagSeeder::class,
            // ParkingLimitSeeder::class,
            // PriceSeeder::class,
            // AgencyPriceSeeder::class,
            // GoodCategorySeeder::class,
            // TagMemberSeeder::class,
            // MemberCarSeeder::class,
            // CarCautionMemberCarSeeder::class,
            // GoodSeeder::class,
            // DealSeeder::class,
        ]);
    }
}

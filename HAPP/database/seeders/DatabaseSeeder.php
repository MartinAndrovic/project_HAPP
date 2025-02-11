<?php

namespace Database\Seeders;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\District;
use App\Models\Region;
use App\Models\User;
use Illuminate\Database\Seeder;
use PhpParser\Node\Stmt\Foreach_;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        /*
        $regions=[
            ['id' =>1, 'name' => 'Bratislavský'],
            ['id' =>2, 'name' => 'Trnavský'],
            ['id' =>3, 'name' => 'Trenčiansky'],
            ['id' =>4, 'name' => 'Nitriannsky'],
            ['id' =>5, 'name' => 'Žilinský'],
            ['id' =>6, 'name' => 'Banskobystrický'],
            ['id' =>7, 'name' => 'Košický'],
            ['id' =>8, 'name' => 'Prešovský'],

        ];

        foreach ($regions as $region) {

            Region::create($region);
        }

        $districts=[
            ['id' =>1, 'region_id' => 1, 'name' => 'Bratislava'],
            ['id' =>2, 'region_id' => 1, 'name' => 'Malacky'],
            ['id' =>3, 'region_id' => 1, 'name' => 'Pezinok'],
            ['id' =>4, 'region_id' => 1, 'name' => 'Senec'],
            ['id' =>5, 'region_id' => 2, 'name' => 'Dunajská Streda'],
            ['id' =>6, 'region_id' => 2, 'name' => 'Galanta'],
            ['id' =>7, 'region_id' => 2, 'name' => 'Hlohovec'],
            ['id' =>8, 'region_id' => 2, 'name' => 'Piešťany'],
            ['id' =>9, 'region_id' => 2, 'name' => 'Senica'],
            ['id' =>10, 'region_id' => 2, 'name' => 'Skalica'],
            ['id' =>11, 'region_id' => 2, 'name' => 'Trnava'],
        ];

        foreach ($districts as $district){
            District::create($district);
        }

*/

       User::factory()
           ->count(50)
           ->create();
    }
}


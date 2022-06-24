<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $sportName = ['Running','Trail','Hiking','Soccer','Workout','Basketball','Biking','Boxing','Tennis','Roller','Skateboard','Yoga','Badmington','Skiing','Snowboarding', 'Surfing', 'Swimming', 'Climbing'];
//
//
//        for ($i = 0; $i <= count($sportName); $i++){
//            DB::table('sports')->insert([
//                'name' => $sportName[$i],
//            ]);
//        }
        DB::table('sports')->insert([
            'name' => 'blbl',
        ]);

    }
}

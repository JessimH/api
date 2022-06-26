<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SportController extends Controller
{
    public function getSports(){
        $sports = DB::table('sports')->get();

        foreach ($sports as $sport){
            $correctSport[] = ['value' => $sport->id, 'label' => $sport->name];
        }

        return $correctSport;
    }
}

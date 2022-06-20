<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExplorerController extends Controller
{
    public function showExplorerContent(){

         $users = DB::table('users')->where('is_pro', 1)->inRandomOrder()->limit(10)->get();

         $sessions = DB::table('sessions')->get();

         $explorerContent['users'] = $users;
         $explorerContent['sessions'] = $sessions;

         return $explorerContent;
    }


    public function showExplorerSearch(){

        // faire la recherche
    }
}

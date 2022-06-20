<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapController extends Controller
{
         public function showSessions(){

             $sessions = DB::table('sessions')->get();

             foreach ($sessions as $session){

                 $user = DB::table('users')->where('id', $session->creator_id)->get();

                 $mapContent['sessions'] = $session;
                 $mapContent['sessions']['user'] = $user;

             }

             return $mapContent;

         }
}

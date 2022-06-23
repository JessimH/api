<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SessionController extends Controller
{
    public function create(Request $request){
        //401 GÉRÉ PAR SANCTUM
        //422 GÉRÉ PAR SANCTUM

//        if (empty($request->session_id) && empty($request->description) && empty($request->medias)){
//            return response()->json(['errors' => "Le post doit au moins contenir soit une scéance, une description ou une photo/video"]);
//        }

        $session = Session::create([
            'sport_id' => $request->sport_id,
            'creator_id' => $request->user()->id,
            'duration' =>$request->duration,
            'coordonate' =>json_encode($request->coordonate)
        ]);

        //STATUS 201
        return response()->json([
            'id' => $session->id,
            'created_at' => $session->created_at,
            'updated_at' => $session->updated_at,
            'sport_id' => $session->sport_id,
            'duration' => $session->duration,
            'creator_id' => $session->creator_id,
            'coordonate' =>json_decode($session->coordonate),
            'user' => [
                'id' => $request->user()->id,
                'created_at' => $request->user()->created_at,
                'updated_at' => $request->user()->updated_at,
                'username' => $request->user()->username,
                'email' => $request->user()->email,
            ]
        ], 201);

    }

    public function deleteSession(Request $request, $id)
    {

        $session = DB::table('sessions')->where('id', $id)->first();

        if ($session){
            if ($session->creator_id !== $request->user()->id){
                return response()->json([
                    'errors' => "Vous n'avez pas l'autorisation de modifier les postes des autres"
                ]);
            }

            $check =  DB::table('sessions')->where('id', $id)->delete();

            if ($check){
                return response()->json([
                    "success" => "La scéance a bien été supprimée"
                ]);
            }
            else{
                return response()->json([
                    "errors" => "La suppression de le scéance n'a pas pu être effectuée"
                ]);
            }
        }

        else{
            return response()->json([
                'errors' => "scéance introuvable"
            ], 404);
        }
    }
}

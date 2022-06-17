<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    //FUNCTION CREATE - nouvelle tache
    public function create(Request $request){
        //401 GÉRÉ PAR SANCTUM
        //422 GÉRÉ PAR SANCTUM

        if (empty($request->session_id) && empty($request->description) && empty($request->medias)){
            return response()->json(['errors' => "Le post doit au moins contenir soit une scéance, une description ou une photo/video"]);
        }

        //Création de la tache avec le body + id de l'user
        $post = Post::create([
            'session_id' => $request->session_id,
            'medias' => $request->medias,
            'description' =>$request->description,
            'user_id' => $request->user()->id,
        ]);

        //STATUS 201
        return response()->json([
            'id' => $post->id,
            'created_at' => $post->created_at,
            'updated_at' => $post->updated_at,
            'description' => $post->description,
            'medias' => $post->medias,
            'isPremium' => $post->isPremium,
            'user' => [
                'id' => $request->user()->id,
                'created_at' => $request->user()->created_at,
                'updated_at' => $request->user()->updated_at,
                'username' => $request->user()->username,
                'email' => $request->user()->email,
            ]
        ], 201);

    }


    public function showAllPosts(Request $request)
    {
        $posts = DB::table('posts')->get();

        return response()->json([
            'posts' => $posts
        ], 200);
    }

    public function showPost(Request $request, $id)
    {

        $post = DB::table('posts as p')->join('users as u', 'u.id', '=','p.user_id' )->select('p.*')->get();

        if(is_null($post)){
            return response()->json([
                'errors' => "L'article n'existe pas."
            ], 404);
        }

        return $post[0]->id;
    }
}

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

    public function showFollowingPosts(Request $request)
    {

        $user = DB::table('users')->select('following')->where('id', request()->user()->id )->get();

        foreach (json_decode($user[0]->following)->list as $followingID){
            $posts= DB::table('posts')->where('user_id', $followingID)->get();
            foreach ($posts as $post){
                $followingPosts[] = $post;

            }
        }

        if(is_null($post)){
            return response()->json([
                'errors' => "Aucun de vos abonnement n'a de post."
            ], 404);
        }

        return $followingPosts;
    }

    public function updatePost(Request $request, $id)
    {

        $post = DB::table('posts')->where('id', $id)->first();

        if ($post->user_id !== $request->user()->id){
            return response()->json([
                'errors' => "Vous n'avez pas l'autorisation de modifier les postes des autres"
            ]);
        }


        $updateValues = [
            'medias' => $request->medias,
            'session_id' => $request->session_id,
            'description' => $request->description,
        ];

//        if($request->image){
//
//            $result = $request->image->storeOnCloudinary();
//
//            $updateValues += [
//                'image' => $result->getSecurePath()
//            ];
//        }

        if ($request->isPremium)
            $updateValues += ['isPremium' => true];
        else
            $updateValues += ['isPremium' => false];


        DB::table('posts')
            ->where(['id' => $post->id])
            ->update($updateValues);

        $post = DB::table('posts')->where('id', $post->id)->first();

        return $post;

    }


    public function deletePost(Request $request, $id)
    {

        $post = DB::table('posts')->where('id', $id)->first();

        if ($post){
            if ($post->user_id !== $request->user()->id){
                return response()->json([
                    'errors' => "Vous n'avez pas l'autorisation de modifier les postes des autres"
                ]);


            }

            $check =  DB::table('posts')->where('id', $id)->delete();

            if ($check){
                return response()->json([
                    "success" => "Le post a bien été supprimé"
                ]);
            }
            else{
                return response()->json([
                    "errors" => "La suppression du post n'a pas pu être effectué"
                ]);
            }
        }

        else{
            return response()->json([
                'errors' => "Post introuvable"
            ]);
        }







    }

}

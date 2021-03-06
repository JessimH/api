<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function Symfony\Component\Mime\Header\get;

class PostController extends Controller
{
    //FUNCTION CREATE - nouvelle tache
    public function create(Request $request){
        //401 GÉRÉ PAR SANCTUM
        //422 GÉRÉ PAR SANCTUM

        if (empty($request->session_id) && empty($request->description) && empty($request->medias)){
            return response()->json(['errors' => "Le post doit au moins contenir soit une scéance, une description ou une photo/video"]);
        }

//        if ($request->medias != null){
//            $picture = cloudinary()->upload("data:image/png;base64,".  $request->medias)->getSecurePath();
//        }

        $post = Post::create([
            'session_id' => $request->session_id,
            //'medias' => $picture,
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

        if (json_decode($user[0]->following)){
            foreach (json_decode($user[0]->following) as $followingID){
                $posts= DB::table('posts')->where('user_id', $followingID)->get();
                foreach ($posts as $post){
                    $user = DB::table('users')->where('id', $post->user_id)->get();
                    $post->user = $user;
                    $followingPosts[] = $post;

                }
            }
        }


        $post = DB::table('posts')->where('user_id', $request->user()->id)->get();

        $followingPosts[] = $post;


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
            ], 404);
        }
    }

    public function addLike(Request $request){

       $post =  DB::table('posts')->where('id', $request->id)->select('likes')->get();

       $decode = json_decode($post[0]->likes);

        if (in_array($request->user()->id, $decode)){
            $newValues = array_diff($decode, [1]);

            $check = DB::table('posts')->where('id', $request->id)->update(['likes'=> json_encode($newValues)]);

            if ($check){
                return response()->json([
                    'success' => 'unlike effectué'
                ]);
            }
        }



       array_push($decode, $request->user()->id);

       $check = DB::table('posts')->where('id', $request->id)->update(['likes'=> json_encode($decode)]);

       if ($check){
           return response()->json([
               'success' => 'like effectué'
           ]);
       }
    }

}

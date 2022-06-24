<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function create(Request $request)
    {

        if (empty($request->body)) {
            return response()->json(['errors' => "Le commentaire ne doit pas etre vide"]);
        }

        $comment = Comment::create([
            'post_id' => $request->post_id,
            'body' => $request->body,
            'user_id' => $request->user()->id,
        ]);

        return response()->json([
            "success" => "le commentaire a bien été créé",
            "comment" => $comment
        ], 201);
    }

    public function deleteComment(Request $request, $id)
    {

        $comment = DB::table('comments')->where('id', $id)->first();

        if ($comment){
            if ($comment->user_id !== $request->user()->id){
                return response()->json([
                    'errors' => "Vous n'avez pas l'autorisation de modifier les commentaires des autres"
                ]);
            }

            $check =  DB::table('comment')->where('id', $id)->delete();

            if ($check){
                return response()->json([
                    "success" => "Le commentaire a bien été supprimé"
                ]);
            }
            else{
                return response()->json([
                    "errors" => "La suppression du commentaire n'a pas pu être effectuée"
                ]);
            }
        }

        else{
            return response()->json([
                'errors' => "commentaire introuvable"
            ]);
        }
    }
}

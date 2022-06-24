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
    }
}

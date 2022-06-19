<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function showMyPosts(Request $request)
    {
        $posts = DB::table('posts')->where('user_id', $request->user()->id)->get();

        return $posts;
    }
}

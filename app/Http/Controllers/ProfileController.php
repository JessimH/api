<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function showProfileContent(Request $request, $id)
    {
        $posts = DB::table('posts')->where('user_id', $id)->get();
        $user = DB::table('users')->where('id', $id)->get();

        $profileContent['posts'] = $posts;
        $profileContent['user'] = $user;

        return $profileContent;
    }

}

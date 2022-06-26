<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isEmpty;

class ProfileController extends Controller
{
    public function showProfileContent($id)
    {
        $user = DB::table('users')->where('id', $id)->get();

        if (isEmpty($user)){
            return response()->json([
                'errors' => "user introuvable"
            ], 404);
        }
        $posts = DB::table('posts')->where('user_id', $id)->get();
        $sessions = DB::table('sessions')->where('creator_id', $id)->get();

        $profileContent['posts'] = $posts;
        $profileContent['user'] = $user;
        $profileContent['sessions'] = $sessions;


        return $profileContent;
    }

    public function updateUser(Request $request, $id)
    {

        $user = DB::table('users')->where('id', $id)->first();

        if ($user->id !== $request->user()->id){
            return response()->json([
                'errors' => "Vous n'avez pas l'autorisation de modifier le profile des autres"
            ]);
        }


        $updateValues = [
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // Profile picture
        ];

//        if($request->image){
//
//            $result = $request->image->storeOnCloudinary();
//
//            $updateValues += [
//                'image' => $result->getSecurePath()
//            ];
//        }


        DB::table('users')
            ->where(['id' => $user->id])
            ->update($updateValues);

        $post = DB::table('users')->where('id', $user->id)->first();

        return $post;

    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
            'sports' => json_encode($request->sports),
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

        $user = DB::table('users')->where('id', $user->id)->first();

        return $user;

    }

    public function addRmFollow(Request $request){

        $user =  DB::table('users')->where('id', $request->id)->select('followers')->get();

        if ($user[0]->followers == null){
            $user[0]->followers = json_encode([]);
        }

        $decode = json_decode($user[0]->followers);

        //UNFOLLOW

        if (in_array($request->user()->id, $decode)){
            $newValues = array_diff($decode, [1]);

            $check = DB::table('users')->where('id', $request->id)->update(['followers'=> json_encode($newValues)]);

            if ($check){
                return response()->json([
                    'success' => 'unfollow effectué'
                ]);
            }
        }

        // FOLLOW

        array_push($decode, $request->user()->id);

        $check = DB::table('users')->where('id', $request->id)->update(['followers'=> json_encode($decode)]);

        if ($check){
            return response()->json([
                'success' => 'follow effectué'
            ]);
        }
    }

}

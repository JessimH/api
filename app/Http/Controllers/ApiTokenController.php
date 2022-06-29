<?php


namespace App\Http\Controllers;

use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ApiTokenController extends Controller
{


    public function register(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'username' => 'required',
            'password' => 'required',
            'sports' => 'required',
        ]);

        $checkUsername = User::where('username', $request->usename)->exists();
        $checkEmail = User::where('email', $request->email)->exists();

        if($checkEmail || $checkUsername){
            return response()->json(['errors' => "Email ou Username déja utilisé"], 409);
        }

        if ($request->picture != null){
            $picture = cloudinary()->upload("data:image/png;base64,".  $request->picture)->getSecurePath();
        }




        $user = User::create([
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'sports' => json_encode($request->sports),
            'is_pro' => $request->is_pro,
            'profile_picture' => $picture,
        ]);



        $token = $user->createToken($request->email)->plainTextToken;

        DB::table('users')->where('email', $request->email)->update(['api_token' => $token]);

        return response()->json([
            'token' => $token,
            'username' => $user->username,
            'email' => $user->email,
            'created_at' => $user->created_at,
            'profile_picture'=> $user->profile_picture
        ], 201);


    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('username', $request->username)->first();

        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json(['errors' => "Identifiants inconnus ou erronés"], 401);
        }

        $user->tokens()->where('tokenable_id', $user->id)->delete();

        $token = $user->createToken($request->username)->plainTextToken;

        DB::table('users')->where('username', $request->username)->update(['api_token' => $token]);


        return response()->json([
            'token' => $token,
            'username' =>$user->username,
            'id'=> $user->id,
            'profile_picture' => $user->profile_picture,
        ], 200);
    }
}

<?php


namespace App\Http\Controllers;

use App\Models\User;
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
            'sports' => 'required'
        ]);

        $checkUsername = User::where('username', $request->usename)->exists();
        $checkEmail = User::where('email', $request->email)->exists();

        if($checkEmail || $checkUsername){
            return response()->json(['errors' => "Email ou Username déja utilisé"], 409);
        }


        $user = User::create([
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'sports' => json_encode($request->sports),
            'following'=> json_encode($request->following),
        ]);

        $token = $user->createToken($request->email)->plainTextToken;

        DB::table('users')->where('email', $request->email)->update(['api_token' => $token]);

        return response()->json([
            'token' => $token
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

        return response()->json([
            'token' => $token,
            'user' =>$user
        ], 200);
    }
}

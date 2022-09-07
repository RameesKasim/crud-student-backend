<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller {
    public function userRegister( Request $request ) {
        $post_data = $request->validate( [
            'username'=>'required|string',
            'email'=>'required|string|email|unique:users',
            'password'=>'required|min:8'
        ] );

        $user = User::create( [
            'name' => $post_data[ 'username' ],
            'email' => $post_data[ 'email' ],
            'password' => Hash::make( $post_data[ 'password' ] ),
        ] );

        $token = $user->createToken( 'authToken' )->plainTextToken;

        return response()->json( [
            'token'=>$token,
            'token_type' => 'Bearer',
            'user'=>$user,
        ] );
    }

    public function userLogin( Request $request ) {
        if ( !\Auth::attempt( $request->only( 'email', 'password' ) ) ) {
            return response()->json( [
                'message' => 'Invalid login credentials'
            ], 400 );
        }

        $user = User::where( 'email', $request[ 'email' ] )->firstOrFail();

        $token = $user->createToken( 'authToken' )->plainTextToken;

        return response()->json( [
            'access_token' => $token,
            'token_type' => 'Bearer',
        ] );
    }

    public function logout() {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Tokens Revoked'
        ];
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login()
    {
        $credentials = request(['login', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                "error" => [
                    "code" => 401,
                    "message" => "Authentication failed"
                ]
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();

        return response()->json([
            "data" => [
                'message' => 'logout'
            ]
        ]);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            "data" => [
                "user_token" => $token
            ]
        ]);
    }
}

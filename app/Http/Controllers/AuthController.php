<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Log;
use App\Models\Invites;

class AuthController extends Controller{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
                'status' => 'success',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);

    }

    public function register(RegisterRequest $request){
        $invite = Invites::where( 'token', $request->token )->first();
        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $invite->email,
            'role' => $invite->role,
            'is_active' => 1,
            'password' => Hash::make($request->password),

        ];
        Invites::destroy($invite->id);
        $user = User::create($data);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
        ]);
    }

    public function me(){
        // Log::error("hi");
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

}

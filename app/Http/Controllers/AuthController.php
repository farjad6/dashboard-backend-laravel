<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Log;
use App\Models\Invites;
use App\Jobs\SendForgetPasswordOtpMailJob;
use App\Models\ForgetPasswordOTP;


class AuthController extends Controller{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register','sendOTPForForgetPassword','verifySendOTPForForgetPassword','changeOTPForForgetPassword']]);
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


    public function sendOTPForForgetPassword(Request $request)
    {
        // Check User Exist
        $Email = $request->get('email');
        if (User::where('email', $Email)->get()->count() > 0) {
            SendForgetPasswordOtpMailJob::dispatch(User::where('email', $Email)->first());
            // Send the response
            return response()->json([
                'status' => true,
                'message' => 'E-Mail sent successfully.'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'email_doesnt_exist' => true,
                'message' => 'E-Mail doesn\'t exist',
            ]);
        }
    }

    public function verifySendOTPForForgetPassword(Request $request)
    {
        $Email = $request->get('email');
        $OTP = $request->get('otp');

        $check = ForgetPasswordOTP::where('email', $Email)->where('otp', $OTP)->first();
        // Check User Exist
        if ($check) {
            return response()->json([
                'status' => true,
                'message' => 'OTP Veirified'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'email_doesnt_exist' => true,
                'message' => 'Wrong OTP',
            ]);
        }
    }

    public function changeOTPForForgetPassword(Request $request) {
        $Email = $request->get('email');
        $OTP = $request->get('otp');
        $Password = $request->get('password');

        $check = ForgetPasswordOTP::where('email', $Email)->where('otp', $OTP)->first();

        if($check) {
            $check->delete();
            $User = User::where('email', $Email)->first();
            $User->password = Hash::make($Password);
            $User->save();

            return response()->json([
                'status' => true,
                'message' => 'Password changed successfully.'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong. Try again later.'
            ], 200);
        }

    }
}

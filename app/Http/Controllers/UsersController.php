<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{
    public function index(Request $request){
        try{ 
            $users = User::get();
            return response()->json([ 'status' => 1 ,'data' => UserResource::collection($users)], 200);

        }catch(\Exception $e){
            customExceptionError([
                "where" => "USERS GET API",
                "error" => $e->getMessage(),
            ]);
            return response()->json([ 'status' => 0 ,'data' => []], 200);
        } 
    }
}

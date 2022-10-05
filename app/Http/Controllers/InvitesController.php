<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invites;
use App\Http\Resources\InvitesResource;
use App\Http\Requests\InviteStoreRequest;
use Illuminate\Support\Facades\Log;

class InvitesController extends Controller
{
    public function index(Request $request){
        try{ 
            $invites = Invites::get();
            return response()->json([ 'status' => 1 ,'data' => InvitesResource::collection($invites)], 200);

        }catch(\Exception $e){
            customExceptionError([
                "where" => "Invites GET API",
                "error" => $e->getMessage(),
            ]);
            return response()->json([ 'status' => 0 ,'data' => []], 200);
        } 
    }

    public function store(InviteStoreRequest $request){
        try{ 
            $data = $request->only(['email', 'role']);
            $data['token'] = bin2hex(random_bytes(20));
            $invite = Invites::create($data);
            return response()->json([ 'status' => 1 ,'data' => InvitesResource::make($invite)], 200);
        }catch(\Exception $e){
            customExceptionError([
                "where" => "Invites STORE API",
                "error" => $e->getMessage(),
            ]);
            return response()->json([ 'status' => 0 ,'data' => []], 200);
        } 
    }

    public function destroy($inviteId)
    {
        try{ 
            Invites::destroy($inviteId);
            return response()->json(['status' => 1, 'message' => 'ok'], 200);
        }catch(\Exception $e){
            customExceptionError([
                "where" => "Invites destroy API",
                "error" => $e->getMessage(),
            ]);
            return response()->json([ 'status' => 0 ,'data' => []], 200);
        } 
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;

class AuthController extends Controller
{
    public function register(UserRequest $request)
    {
        $request->validated();
        return $request->register($request->toArray());        
    }

    public function logout (Request $request)
    {      
        auth()->user()->tokens()->delete();
        return response()->json(['Logged out.'], User::USER_LOG_OUT);
    }

    public function login(UserRequest $request)
    {
        $request->validated();
        return $request->login($request->toArray());        
    }
}

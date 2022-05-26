<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\BaseRequest;

class UserRequest extends BaseRequest
{
     /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if($this->path() == 'api/login'){
            return [
                'email' => 'required|email',
                'password' => 'required|string'
            ];
        }
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed'
        ];
    }

   public function register(array $request)
   {
       $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
       ]);
       $token = $user->createToken('mytoken')->plainTextToken;
       return response()->json(['data'=>$user, 'token'=>$token], User::USER_SUCCESSFULLY_CREATED);
   }

   public function login(array $request)
   {
        $user = User::where('email', $request['email'])->first();
        if(!$user || !Hash::check($request['password'], $user->password)) {
            return response(['message'=>'Bad credential.'], User::USER_BAD_CREDENTIAL);
        }
        $token = $user->createToken('mytoken')->plainTextToken;
        return response()->json(['data'=>$user, 'token'=>$token], User::USER_SUCCESSFULLY_LOGGED);
   }
}

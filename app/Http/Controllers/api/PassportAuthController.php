<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Profile;

class PassportAuthController extends Controller
{
    public function register(Request $request){
        $this->validate($request, [
            'name'=> 'required',
            'lastname'=> 'required',
            'username'=> 'required',
            'email' => 'required|email',
            'password' => 'required',
            'profile_id'=> 'required'
        ]);

        $user = User::create([
            'name'=> $request->name,
            'lastname'=> $request->lastname,
            'username'=> $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'profile_id' => $request->profile_id,
        ]);

        $token = $user->createToken('ColombianRestaurant')->accessToken;

        return response()->json(['token' => $token], 200);
    }

    public function login(Request $request){

        $data = $request->all();
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);
  
        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if(Auth::attempt( [$fieldType=>$data['username'], 'password' => $data['password']] )){
            
            $user = Auth::user();
            $userProfile = $user->profile()->first();
              
            if ($userProfile) {
                $this->scope = $userProfile->type;
            }

            $token = $user->createToken($user->email.'-'.now(), [$this->scope]);

            return response()->json([
                'token' => $token->accessToken
            ]);
        }else{
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }
}

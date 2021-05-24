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
        $user = User::create([
            'name'=> $request->name,
            'lastname'=> $request->lastname,
            'username'=> $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'profile_id' => $request->profile_id,
            'branch_office_id' => $request->branch_office_id,
        ]);
        $profile = $user->profile()->first();

        $token = $this->createToken($user);

        $branch = $user->branch()->orWhere('id', $user->branch_office_id)->first();
        $user->city_id = $branch->city_id;

        return response()->json([
            'token' => $token->accessToken,
            'profile' => $profile,
            'user' => $user
        ]);
    }

    public function login(Request $request){

        $data = $request->all();
  
        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if(Auth::attempt( [$fieldType=>$data['username'], 'password' => $data['password']] )){
            
            $user = Auth::user();
            $profile = $user->profile()->first();
            
            $token = $this->createToken($user);

            $branch = $user->branch()->orWhere('id', $user->branch_office_id)->first();
            if(isset($branch->city_id)){
                $user->city_id = $branch->city_id;
            }

            return response()->json([
                'token' => $token->accessToken,
                'profile' => $profile,
                'user' => $user
            ]);
        }else{
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    public function createToken($user){
        $userProfile = $user->profile()->first();
              
        if ($userProfile) {
            $this->scope = $userProfile->type;
        }

        return $user->createToken($user->email.'-'.now(), [$this->scope]);
    }
}

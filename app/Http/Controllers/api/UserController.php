<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::select("users.id", "users.name", "lastname", "username", "email", "profile_id", "profiles.type as profile", "branch_office_id", "branches.name as branch")
                            ->leftJoin('profiles', 'users.profile_id', '=', 'profiles.id')
                            ->leftJoin('branches', 'users.branch_office_id', '=', 'branches.id')
                            ->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = User::create([
            'name'=> $request->name,
            'lastname'=> $request->lastname,
            'username'=> $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'profile_id' => $request->profile_id,
            'branch_office_id' => $request->branch_office_id,
        ]);

        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'User not found.'
            ], 403);
        }

        if(!empty($request->password)){
            if (!Hash::check($request->password, $user->password)) {
                User::find($user->id)->update(['password'=> bcrypt($request->password)]);
            }
        }

        $user->update([
            'name'=> $request->name,
            'lastname'=> $request->lastname,
            'username'=> $request->username,
            'email' => $request->email,
            'profile_id' => $request->profile_id,
            'branch_office_id' => $request->branch_office_id,
        ]);

        return response()->json($user, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'User not found.'
            ], 403);
        }
        
        $user->delete();
        return response()->json(['message'=>'User deleted successfully.'], 200);
    }
}

<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\Modifier;
use App\Models\ModifierGroup;
use Illuminate\Http\Request;

class ModifierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $modifier = Modifier::select('id', 'name', 'price')->with([
            'ModifierGroup' => function ($query) {
                $query->select('modifier_group_id');
            }
        ])->get();

        echo(json_encode($modifier));
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
        $modifier = Modifier::create([
            'name' => $request->name,
            'price' => $request->price
        ]);

        foreach (json_decode($request->modifier_group) as $value){
            $modifierGroupId = ModifierGroup::findOrFail($value); 
            $modifier->modifierGroup()->attach($modifierGroupId);
        }

        return response()->json($modifier, 201);
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
            $modifier = Modifier::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Modifier not found.'
            ], 403);
        }

        $modifier->update([
            'name' => $request->name,
            'price' => $request->price
        ]);

        $modifier->modifierGroup()->sync(json_decode($request->modifier_group));

        // foreach(json_decode($request->modifier_group) as $value){
        //     $modifier->modifierGroup()->updateExistingPivot($id, [
        //         "modifier_group_id" => $value
        //     ]);
        // }

        return response()->json($modifier, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modifier = Modifier::find($id);

        try {
            $modifier = Modifier::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Modifier not found.'
            ], 403);
        }

        $modifier->modifierGroup()->detach();
        $modifier->delete();

        return response()->json(['message'=>'Modifier deleted successfully.'], 200);
    }
}

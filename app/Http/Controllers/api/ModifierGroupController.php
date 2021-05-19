<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Http\Request;

use App\Models\ModifierGroup;

class ModifierGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ModifierGroup::with('categories')->get();
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
        $modifierGroup = ModifierGroup::create([
            'name' => $request->name,
            'selection_type' => $request->selection_type,
        ]);

        $modifierGroup->categories()->attach($request->categories_ids);

        return response()->json($modifierGroup, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
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
            $modifierGroup = ModifierGroup::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Modifier group not found.'
            ], 403);
        }

        $modifierGroup->update([
            'name' => $request->name,
            'selection_type' => $request->selection_type,
        ]);

        $modifierGroup->categories()->sync($request->categories_ids);

        return response()->json($modifierGroup, 200);
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
            $modifierGroup = ModifierGroup::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Modifier group not found.'
            ], 403);
        }
        
        $modifierGroup->delete();
        return response()->json(['message'=>'Modifier deleted successfully.'], 200);
    }
}

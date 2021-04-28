<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Modifier;
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
        return Modifier::select('id', 'name', 'price')->get();
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
        $modifier = Modifier::find($id);

        try {
            $modifier = Modifier::findOfFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Modifier not found.'
            ], 403);
        }

        $modifier->update([
            'name' => $request->name,
            'price' => $request->price
        ]);

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
            $modifier = Modifier::findOfFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Modifier not found.'
            ], 403);
        }
        
        $modifier->delete();
        return response()->json(['message'=>'Modifier deleted successfully.'], 200);
    }
}

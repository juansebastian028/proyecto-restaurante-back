<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Product;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Branch::select("branches.id", "branches.name", "city_id", "cities.name as city")
                            ->leftJoin('cities', 'city_id', '=', 'cities.id')
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
        $branch = Branch::create([
            'name' => $request->name,
            'city_id' => $request->city_id
        ]);

        return response()->json($branch, 201);
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
        $branch = Branch::find($id);

        try {
            $branch = Branch::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Branch not found.'
            ], 403);
        }

        $branch->update([
            'name' => $request->name,
            'city_id' => $request->city_id
        ]);

        return response()->json($branch, 200);
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
            $branch = Branch::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Branch not found.'
            ], 403);
        }
        
        $branch->delete();
        return response()->json(['message'=>'Branch deleted successfully.'], 200);
    }

    public function getProductsByBranch($id){
        try {
            $branch = Branch::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Branch not found.'
            ], 403);
        }

        $branchWithProducts = $branch->with(['products'=> function ($query) use ($id){
            $query->wherePivot('branch_id', $id);
        }])->where('id', $id)->get();

        $products = $branchWithProducts[0]->products;
        $newProducts = [];
        
        foreach ($products as $product){
            $productsWith = Product::select('products.id', 'products.name', 'price','categories.name as category')
            ->join('categories', 'products.category_id', '=', 'categories.id')->find($product->id);
            array_push($newProducts, $productsWith);
        }

        return response()->json($newProducts, 200);
    }
}

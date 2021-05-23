<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Product;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return City::select('id', 'name')->get();
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
        $city = City::create([
            'name' => $request->name
        ]);

        return response()->json($city, 201);
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
            $city = City::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'City not found.'
            ], 403);
        }

        $city->update(['name' => $request->name]);

        return response()->json($city, 200);
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
            $city = City::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'City not found.'
            ], 403);
        }
        
        $city->delete();
        return response()->json(['message'=>'City deleted successfully.'], 200);
    }

    public function getProductsByCity($id){
        $products = $this->getProductsBranchByCity($id);
        $newProducts = [];
        foreach ($products as $product){
            $productsWith = Product::with('category','category.modifierGroups','category.modifierGroups.modifier')->find($product->id);
            array_push($newProducts, $productsWith);
        }

        return response()->json($newProducts, 200);
    }

    public function searchProducts(Request $request, $id){
        $products = $this->getProductsBranchByCity($id);
        $newProducts = [];
        foreach ($products as $product){
            $productsWith = Product::with('category','category.modifierGroups','category.modifierGroups.modifier')
            ->where('name', 'LIKE', '%'.$request->search.'%')
            ->find($product->id);

            if($productsWith){
                array_push($newProducts, $productsWith);
            }
        }

        return response()->json($newProducts, 200);
    }

    public function getProductsByCategory($city_id, $category_id){
        $products = $this->getProductsBranchByCity($city_id);
        $newProducts = [];
        foreach ($products as $product){
            $productsWith = Product::with('category','category.modifierGroups','category.modifierGroups.modifier')
            ->where('category_id', $category_id)
            ->find($product->id);

            if ($productsWith !== null) {
                array_push($newProducts, $productsWith);
            }
        }

        return response()->json($newProducts, 200);
    }

    public function getProductsBranchByCity($id){
        
        try {
            $city = City::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'City not found.'
            ], 403);
        }

        $cityWithBranch = $city->with(['branch'=> function ($query) use ($id){
            $query->where('city_id', $id);
        }])->where('id', $id)->get();

        $branch = $cityWithBranch[0]->branch;
        $branch_id = $cityWithBranch[0]->branch->id;

        $branchWithProducts = $branch->with(['products'=> function ($query) use ($branch_id){
            $query->wherePivot('branch_id', $branch_id)->wherePivot('state','A');
        }])->where('id', $branch_id)->get();

        return $branchWithProducts[0]->products;
    }
}

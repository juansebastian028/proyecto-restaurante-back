<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Http\Request;

use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::select('products.id', 'products.name', 'price','img','category_id','categories.name as category')
        ->join('categories', 'products.category_id', '=', 'categories.id')
        ->with('branches')
        ->get();
        
        return response()->json($products, 200);
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
        if($image = $request->file('img')){
            $img_name = $image->getClientOriginalName();
            $image->move('uploads', $image->getClientOriginalName());
        }

        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'img' => asset('/uploads/' . $img_name),
            'category_id' => $request->category_id
        ]);

        $product->branches()->attach(json_decode($request->branches_ids), array('state' => 'I'));

        return response()->json($product, 200);
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
            $product = Product::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Product not found.'
            ], 403);
        }

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'img' => $request->img,
            'category_id' => $request->category_id
        ]);

        $product->branches()->wherePivot('state','I')->detach();

        $branches = $product->branches()->get();

        $branches_ids = $request->branches_ids;

        for($i = 0; $i < count($branches_ids); $i++){
            $flag = false;
            for($j = 0; $j < count($branches); $j++){
                if($branches_ids[$i] == $branches[$j]->pivot->branch_id){
                    $flag = true;
                }
            }
            if(!$flag){

                $product
                ->branches()
                ->attach($branches_ids[$i], ['product_id' =>  $product->id, 'state' => 'I']);
            }
        }

        return response()->json($product, 200);
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
            $product = Product::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Product not found.'
            ], 403);
        }
        $product->delete();
        return response()->json(['message'=>'Product deleted successfully.'], 200);
    }
}

<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Http\Request;

use App\Models\ShoppingCart;
use App\Models\User;

class ShoppingCartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $shoppingCart = ShoppingCart::create([
            'unit_price' => $request->unit_price,
            'quantity' => $request->quantity,
            'total' => $request->total,
            'product_id' => $request->product_id,
            'user_id' => $request->user_id
        ]);

        $shoppingCart->modifiers()->attach(json_decode($request->modifiers, true));

        return response()->json($shoppingCart, 200);
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

    public function showByUser($id){
        try {
            $user = User::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Shopping cart not found.'
            ], 403);
        }

        return ShoppingCart::select("shopping_cart.id","unit_price", "quantity","total", "products.name as product", "products.img as image")
        ->join('products', 'shopping_cart.product_id', '=', 'products.id')->where('shopping_cart.user_id', '=', $id)
        ->get();
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
        //
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
            $shoppingCart = ShoppingCart::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Shopping cart not found.'
            ], 403);
        }
        $shoppingCart->delete();
        return response()->json(['message'=>'Shopping cart deleted successfully.'], 200);
    }
}

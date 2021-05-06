<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Order;
use App\Models\User;
use App\Models\ShoppingCart;
use App\Models\Branch;

class OrderController extends Controller
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
        // DB::beginTransaction();
        $order = Order::create([
            'user_id' => $request->user_id,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'state'=>'P'
        ]);
        
        $shopping_cart_ids = json_decode($request->shopping_cart_ids);

        for($i = 0; $i < count($shopping_cart_ids); $i++){
            $shopping_cart = ShoppingCart::find($shopping_cart_ids[$i]);
            $product = $shopping_cart->product()->first();

            $order->products()->attach($order->id, [
                "product_id" => $shopping_cart->product_id,
                "quantity" => $shopping_cart->quantity,
                "unit_price" => $product->price,
                "total" => $product->price * $shopping_cart->quantity
            ]);

            $order_product = $order->products()->get();

            echo(json_encode($order_product));

            // $shopping_cart->modifiers()->newPivotStatement()
            //     ->where('shopping_cart_id', $shopping_cart->id)
            //     ->update([
            //         'shopping_cart_id' => null,
            //         'order_product_id' => $order_product->id
            //     ]);

            // $shopping_cart->delete();
        }
        // DB::rollBack();
        return response()->json($order, 201);
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

    public function showByUser($id){
        try {
            $user = User::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'User not found.'
            ], 403);
        }

        return Order::select('orders.id','address','phone_number','state', DB::raw('SUM(total) as total_order'))
        ->join('order_product','orders.id','=', 'order_product.order_id')
        ->where('order.user_id', '=', $id)
        ->groupBy('orders.id','address','phone_number','state')->get();
    }

    public function showByBranch($id){
        try {
            $branch = Branch::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Branch not found.'
            ], 403);
        }

        return Order::select('orders.id','address','phone_number','orders.state', DB::raw('SUM(total) as total_order'))
        ->leftJoin('order_product','orders.id','=', 'order_product.order_id')
        ->where('orders.branch_id', '=', $id)
        ->groupBy('orders.id','address','phone_number','orders.state')->get();
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
            $order = Order::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Order not found.'
            ], 403);
        }

        $order->update(['state' => $request->state]);

        return response()->json($order, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

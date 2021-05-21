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
use App\Models\ModifierGroup;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Order::select('orders.id','address','phone_number',
        DB::raw("DATE_FORMAT(orders.created_at,'%Y/%m/%d') AS date"),
            DB::raw("CASE state WHEN 'P' THEN 'En Proceso' WHEN 'C' THEN 'Cancelado' WHEN 'F' THEN 'Finalizado' END AS state"), 
            DB::raw('SUM(total) as total_order'), DB::raw("CONCAT(MAX(users.name), ' ', MAX(users.lastname)) as user"))
        ->join('order_product','orders.id','=', 'order_product.order_id')
        ->leftJoin('users','orders.user_id','=', 'users.id')
        ->groupBy('orders.id','address','phone_number','state', 'orders.created_at')->get();
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
        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => $request->user_id,
                'branch_id' => $request->branch_id,
                'address' => $request->address,
                'phone_number' => $request->phone_number,
                'state'=>'P'
            ]);
            
            $shopping_cart_ids = $request->shopping_cart_ids;
    
            for($i = 0; $i < count($shopping_cart_ids); $i++){
    
                $shopping_cart = ShoppingCart::find($shopping_cart_ids[$i]['id']);
                $product = $shopping_cart->product()->first();
    
                $order->products()->attach($product->id, [
                    "product_id" => $shopping_cart->product_id,
                    "quantity" => $shopping_cart->quantity,
                    "unit_price" => $product->price,
                    "total" => $product->price * $shopping_cart->quantity
                ]);

                $order_product_id = $order->products()
                ->where('product_id', $shopping_cart->product_id)
                ->get()[0]->pivot->id;
    
                $shopping_cart->modifiers()->newPivotStatement()
                ->where('shopping_cart_id', $shopping_cart->id)
                ->update([
                    'shopping_cart_id' => null,
                    'order_product_id' => $order_product_id
                ]);
    
                $shopping_cart->delete();
            }
            
            DB::commit();
            return response()->json($order, 201);
        } catch(Exception $e) {
            DB::rollBack();
            return response()->json($e, 402);
        }
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
        ->where('orders.user_id', '=', $id)
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

    public function showProductsOrder($id){
        try {
            Order::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Order not found.'
            ], 403);
        }
        
        $order = Order::find($id)::with('products')->where('id', $id)->get()[0];

        $products = $order->products;

        foreach ($products as $product){
            $categories = DB::table('categories')->where('id', $product->category_id)->get();
            foreach ($categories as $category){
                $modifier_groups = DB::table('modifier_groups')->where('category_id', $product->category_id)->get();
                foreach ($modifier_groups as $modifier_group){
                    
                    $modifiers = DB::table('modifier_modifier_group')
                    ->leftJoin('shopping_cart_modifier', 'modifier_modifier_group.modifier_id', '=', 'shopping_cart_modifier.modifier_id')
                    ->leftJoin('modifiers', 'shopping_cart_modifier.modifier_id', '=', 'modifiers.id')
                    ->where('shopping_cart_modifier.order_product_id', $product->pivot->id)
                    ->where('modifier_modifier_group.modifier_group_id', $modifier_group->id)
                    ->get();

                    $modifier_group->modifiers = $modifiers;
                }
                $category->modifier_groups = $modifier_groups;
            }
            $product->category = $category;
        }
        $order->products = $products;

        return response()->json($order, 200);
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

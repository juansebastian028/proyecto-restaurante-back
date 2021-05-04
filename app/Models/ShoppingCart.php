<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Product;
use App\Models\Modifier;
use App\Models\Order;
use App\Models\User;

class ShoppingCart extends Model
{
    use HasFactory;

    protected $table = "shopping_cart";

    protected $fillable = [
        'unit_price',
        'quantity',
        'total',
        'product_id',
        'user_id'
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function modifiers(){
        return $this->belongsToMany(Modifier::class, 'shopping_cart_modifier', 'shopping_cart_id', 'modifiers_id')
        ->withPivot('unit_price_modifier');
    }

    public function order(){
        return $this->belongsToMany(Order::class, 'order_shopping_cart', 'order_id', 'shopping_cart_id');
    }
}

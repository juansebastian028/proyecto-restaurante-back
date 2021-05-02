<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Product;
use App\Models\Modifier;
use App\Models\Order;

class ShoppingCart extends Model
{
    use HasFactory;

    protected $table = "shopping_cart";

    protected $fillable = [
        'unit_price',
        'quantity',
        'total',
        'product_id'
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function modifiers(){
        return $this->belongsToMany(Modifier::class, 'shopping_cart_modifier', 'shopping_cart_id', 'modifiers_id')
        ->withPivot('unit_price_modifier');
    }

    public function order(){
        return $this->hasMany(Order::class);
    }
}

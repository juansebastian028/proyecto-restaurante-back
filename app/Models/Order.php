<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Product;
use App\Models\Modifier;
use App\Models\ShoppingCart;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'address',
        'phone_number',
        'cancellation_description',
        'state',
        'user_id',
        'branch_id'
    ];
    
    public function shoppingCart(){
        return $this->belongsToMany(ShoppingCart::class);
    }

    public function user(){
        return $this->belongsToMany(User::class);
    }

    public function products(){
        return $this->belongsToMany(Product::class, 'order_product')
        ->withPivot('id', 'product_id', 'quantity', 'unit_price', 'total');
    }

    public function modifier(){
        return $this->belongsToMany(Modifier::class, 'shopping_cart_modifier')
        ->withPivot('unit_price_modifier');
    }
}

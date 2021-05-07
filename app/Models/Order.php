<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
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
    ];
    
    public function shoppingCart(){
        return $this->belongsToMany(ShoppingCart::class);
    }

    public function user(){
        return $this->belongsToMany(User::class);
    }

    public function products(){
        return $this->belongsToMany(Order::class, 'order_product')
        ->withPivot('id', 'product_id', 'quantity', 'unit_price', 'total');
    }
}

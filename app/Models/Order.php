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
    ];
    
    public function shoppingCart(){
        return $this->belongsToMany(ShoppingCart::class);
    }
}

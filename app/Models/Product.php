<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Category;
use App\Models\Branch;
use App\Models\ShoppingCart;
use App\Models\Order;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'img',
        'category_id'
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function branches(){
        return $this->belongsToMany(Branch::class,'product_branch', 'product_id', 'branch_office_id')
        ->withPivot('state');
    }

    public function shoppingCart(){
        return $this->hasMany(ShoppingCart::class);
    }

    public function orders(){
        return $this->belongsToMany(Order::class);
    }
}

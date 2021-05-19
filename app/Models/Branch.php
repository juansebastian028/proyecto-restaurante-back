<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\City;
use App\Models\Product;
use App\Models\User;

class Branch extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'city_id'
    ];

    public function city(){
        return $this->belongsTo(City::class);
    }

    public function products(){
        return $this->belongsToMany(Product::class, 'product_branch')
        ->withPivot('state');
    }

    public function user(){
        return $this->hasOne(User::class);
    }

}

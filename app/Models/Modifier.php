<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ModifierGroup;
use App\Models\ShoppingCart;

class Modifier extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'price'
    ];

    public function modifierGroup(){
        return $this->belongsToMany(ModifierGroup::class);
    }

    public function shoppingCart(){
        return $this->belongsToMany(ShoppingCart::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Product;
use App\Models\ModifierGroup;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    public function product(){
        return $this->hasOne(Product::class);
    }

    public function modifierGroups(){
        return $this->hasMany(ModifierGroup::class);
    }
}

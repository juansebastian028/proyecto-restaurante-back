<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Modifier;
use App\Models\Category;

class ModifierGroup extends Model
{
    use HasFactory;

    protected $hidden = array('pivot');
    
    protected $fillable = [
        'name',
        'selection_type',
    ];

    public function modifier(){
        return $this->belongsToMany(Modifier::class);
    }

    public function categories(){
        return $this->belongsToMany(Category::class, 'modifier_group_category');
    }
}

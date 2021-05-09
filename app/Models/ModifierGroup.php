<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Modifier;

class ModifierGroup extends Model
{
    use HasFactory;

    protected $hidden = array('pivot');
    
    protected $fillable = [
        'name',
        'selection_type',
        'category_id'
    ];

    public function modifier(){
        return $this->belongsToMany(Modifier::class);
    }

    public function categories(){
        return $this->belongsToMany(ModifierGroup::class);
    }
}

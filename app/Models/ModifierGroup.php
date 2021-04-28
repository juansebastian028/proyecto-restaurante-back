<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModifierGroup extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'selection_type',
        'category_id'
    ];
}

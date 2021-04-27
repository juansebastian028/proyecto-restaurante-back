<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Branch;

class City extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    public function branch(){
        return $this->hasOne(Branch::class);
    }
}

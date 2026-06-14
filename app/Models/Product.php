<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{

    use SoftDeletes;
    
    protected $fillable = [
        'name',
        'price',
        'stock'
    ];

    public function orders()
    {
        //pertenece a varias ordenes
        return $this->belongsToMany(Order::class);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    
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

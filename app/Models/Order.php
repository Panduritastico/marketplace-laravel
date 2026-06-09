<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'serie',
        'correlative',
        'estado',
        'total'
    ];

    //Un usuario puede tener varias ordenes
    //Una ORDEN puede tener 1 USUARIO a la vez
    //Muchos a uno
    public function user()
    {
        //pertenece a un usuario
        return $this->belongsTo(User::class);
    }

    //Un PRODUCTO puede estar en VARIAS ordenes
    //Una ORDEN puede tener VARIOS productos
    //Muchos a muchos
    public function products()
    {
        //pertenece varios productos
        return $this->belongsToMany(Product::class);
    }
}

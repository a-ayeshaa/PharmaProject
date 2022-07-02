<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    use HasFactory;
    protected $table='orders';
    public function accepted()
    {
        return $this->hasMany(accepted_order::class,'order_id','order_id');
    }

    public function carts()
    {
        return $this->hasOne(carts::class,'cart_id','cart_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vendor extends Model
{
    use HasFactory;
    protected $table='vendors';

    public function supply()
    {
        return $this->hasMany(vendor::class,'vendor_id','vendor_id');
    }

    public function contract()
    {
        return $this->hasMany(contract::class,'vendor_id','vendor_id');
    }

    public function users()
    {
        return $this->belongsTo(users::class,'vendor_id','u_id');
    }
}

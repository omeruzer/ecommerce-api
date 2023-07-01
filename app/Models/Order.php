<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $guarded = [];

    public function products()
    {
        return $this->hasMany(CartProducts::class, 'cart_id', 'id');
    }

    public function status()
    {
        return $this->hasOne(ShippingStatus::class ,'id', 'status_id',);
    }

}

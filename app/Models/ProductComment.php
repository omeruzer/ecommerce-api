<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductComment extends Model
{
    use HasFactory;

    protected $table = 'product_comments';
    protected $guarded = [];

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }
}

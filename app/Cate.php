<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cate extends Model
{
    //
    protected $fillable=[
        'goodsCount','goodsList','user_id'
    ];

    public function address()
    {
        return $this->belongsTo('address','goodsList');
        }
}


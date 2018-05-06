<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    //
    protected $fillable=[
        'tel','name','provence','city','area','detail_address','user_id'
    ];
}

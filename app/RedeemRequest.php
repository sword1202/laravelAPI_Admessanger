<?php

namespace App;


use Illuminate\Database\Eloquent\Model;


class RedeemRequest extends Model
{
    

    public $table = 'redeem_request';

    public $fillable = ['user_id', 'amount', 'status','item_id'];

    
}
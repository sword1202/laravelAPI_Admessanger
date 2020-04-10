<?php

namespace App;


use Illuminate\Database\Eloquent\Model;


class RedeemPayout extends Model
{
  public $table = 'redeem_payout';
  public $fillable = ['user_id','amount','payout_batch_id','batch_status','sender_batch_id','item_id','item_status'];
}

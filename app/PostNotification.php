<?php

namespace App;


use Illuminate\Database\Eloquent\Model;


class PostNotification extends Model
{
    

    public $table = 'post_notification';

    public $fillable = ['post_sender_id','post_receiver_id','post_id','amount','post_date','status','redeem_request'];

    
}

<?php

namespace App;


use Illuminate\Database\Eloquent\Model;


class UserNotification extends Model
{
    

    public $table = 'user_notification';

    public $fillable = ['user_id','sender_id','batch_count','notification_type','status'];

    
}

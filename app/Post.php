<?php

namespace App;


use Illuminate\Database\Eloquent\Model;


class Post extends Model
{
    

    public $table = 'create_post';

    public $fillable = ['user_id','title','description','total_notification','amount','post_type','type','transaction_id','create_time'];

    
}

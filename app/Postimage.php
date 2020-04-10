<?php

namespace App;


use Illuminate\Database\Eloquent\Model;


class Postimage extends Model
{
    

    public $table = 'post_images';

    public $fillable = ['user_id', 'post_id', 'images', 'image_thumbnail'];

    
}
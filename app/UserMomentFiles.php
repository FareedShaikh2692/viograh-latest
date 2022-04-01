<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserMomentFiles extends Model
{
    
    public $timestamps = false;
    protected $fillable = [
        'feed_id', 'user_id',  'file', 'created_at','created_ip'
    ];

   
}

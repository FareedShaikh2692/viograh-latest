<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name', 'email', 'subject', 'message', 'is_read'.'status','created_at','updated_at','created_ip','updated_ip'
    ];
}

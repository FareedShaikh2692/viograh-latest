<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    protected $fillable = [
        'name','setting_key', 'value', 'setting_type','status','created_by','updated_by','created_ip','updated_ip'
    ];

}

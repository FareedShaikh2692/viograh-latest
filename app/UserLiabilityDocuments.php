<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLiabilityDocuments extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'liability_id', 'file', 'created_at','created_ip'
    ];
}

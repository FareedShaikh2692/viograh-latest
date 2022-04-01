<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAssetDocuments extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'asset_id', 'file', 'created_at','created_ip'
    ];
    public function get_asset(){
        return $this->hasOne('App\UserAsset','id','asset_id');
    }
}

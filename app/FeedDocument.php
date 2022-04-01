<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeedDocument extends Model
{
    public function get_feed_details()
    {
        return $this->hasOne('App\UserFeed','id','feed_id');
    }
}

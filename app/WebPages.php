<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebPages extends Model
{
    protected $table = 'web_pages';
    protected $fillable = [
        'page_title','page_heading','page_content', 'slug', 'meta_tag', 'meta_description','status','created_by','updated_by','created_ip', 'updated_ip'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    //栏目
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    //用户
    public function admin()
    {
        return $this->belongsTo('App\Models\Admin');
    }
}

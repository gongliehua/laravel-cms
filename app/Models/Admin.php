<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    //用户组
    public function group()
    {
        return $this->belongsTo('App\Models\Group');
    }
}

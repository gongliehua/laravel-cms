<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    //权限
    public function rule()
    {
        return $this->belongsToMany('App\Models\Rule','group_rules');
    }
}

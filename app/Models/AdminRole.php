<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model
{
    // 获取角色
    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }
}

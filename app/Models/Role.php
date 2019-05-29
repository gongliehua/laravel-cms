<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    // 获取权限
    public function permission()
    {
        return $this->belongsToMany('App\Models\Permission','role_permissions');
    }
}

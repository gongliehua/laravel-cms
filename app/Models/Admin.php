<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable;

    // 获取管理员角色关系
    public function adminRole()
    {
        return $this->hasOne('App\Models\AdminRole');
    }
}

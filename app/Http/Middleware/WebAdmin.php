<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use Closure;
use Illuminate\Support\Facades\Auth;

class WebAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 验证是否登录
        if (!Auth::guard('webAdmin')->check()) {
            return redirect('admin/login');
        }

        // 需要排除的路由
        $except = ['admin/index', 'admin/profile'];
        if (!in_array($request->path(), $except)) {
            // 验证权限
            if (Auth::guard('webAdmin')->id() != '1') {
                $admin = Admin::with(['adminRole'=>function($query) {
                    $query->with(['role'=>function($query) {
                        $query->with(['permission']);
                    }]);
                }])->where('id',Auth::guard('apiAdmin')->id())->first()->toArray();
                if (!isset($admin['admin_role']['role']['status']) || $admin['admin_role']['role']['status'] != '1') {
                    return '<h1 style="font-weight: normal;">权限不足！</h1>';
                }
                if (!isset($admin['admin_role']['role']['permission']) || !in_array($request->path(), array_column($admin['admin_role']['role']['permission'], 'uri'))) {
                    return '<h1 style="font-weight: normal;">权限不足！</h1>';
                }
            }
        }
        return $next($request);
    }
}

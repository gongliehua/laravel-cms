<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use App\Models\GroupRule;
use App\Models\Rule;
use Closure;

class AdminLogin
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
        if (!$request->session()->get('admin.id')) return redirect('admin/login');
        // 默认用户不限制
        if ($request->session()->get('admin.id') !== 1) {
            $except = ['admin/index','admin/profile','admin/error'];
            if (!in_array($request->path(),$except)) {
                $rule = Rule::where('name',$request->path())->first();
                if (!$rule || $rule->status !== 1) return redirect('admin/error');

                $admin = Admin::with(['group'])->find($request->session()->get('admin.id'));
                if (!$admin || $admin->group->status !== 1) return redirect('admin/error');

                $group_rule = GroupRule::where(['group_id'=>$admin->group->id,'rule_id'=>$rule->id])->first();
                if (!$group_rule) return redirect('admin/error');
            }
        }
        return $next($request);
    }
}

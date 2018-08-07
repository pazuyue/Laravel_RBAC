<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ClearanceMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (Auth::user()->hasPermissionTo('角色&权限管理'))
        {
            return $next($request);  // 管理员具备所有权限
        }

        if ($request->is('posts/create')) // 文章发布权限
        {
            if (!Auth::user()->hasPermissionTo('新建文章'))
            {
                abort('401');
            }
            else {
                return $next($request);
            }
        }

        if ($request->is('posts/*/edit')) // 文章编辑权限
        {
            if (!Auth::user()->hasPermissionTo('修改文章')) {
                abort('401');
            } else {
                return $next($request);
            }
        }

        if ($request->isMethod('Delete')) // 文章删除权限
        {
            if (!Auth::user()->hasPermissionTo('删除文章')) {
                abort('401');
            }
            else
            {
                return $next($request);
            }
        }

        return $next($request);
    }
}

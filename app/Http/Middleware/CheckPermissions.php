<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermissions
{

    public function handle(Request $request, Closure $next ,$permission,$field='can_show'): Response
    {
        $user = Auth::user();

        if (!$user || optional($user->permissions($permission))->$field != 1) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}

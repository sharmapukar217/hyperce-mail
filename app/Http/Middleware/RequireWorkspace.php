<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Facades\HyperceMail;
use Closure;
use RuntimeException;

class RequireWorkspace
{
    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|mixed
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle($request, Closure $next)
    {
        try {
            HyperceMail::currentWorkspaceId();
        } catch (RuntimeException $exception) {
            if ($request->is('api/*')) {
                return response('Unauthorized.', 401);
            }

            abort(404);
        }

        return $next($request);
    }
}

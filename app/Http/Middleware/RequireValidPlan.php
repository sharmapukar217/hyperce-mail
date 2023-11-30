<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Facades\HyperceMail;
use App\Models\Plan;
use Closure;
use RuntimeException;

class RequireValidPlan
{
    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|mixed
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle($request, Closure $next)
    {
        try {
            $workspace_id = HyperceMail::currentWorkspaceId();
            $plan = Plan::where('workspace_id', $workspace_id)->first();

            if (! $plan) {
                return redirect(route('plans.show'));
            }

        } catch (RuntimeException $exception) {
            if ($request->is('api/*')) {
                return response('Unauthorized.', 401);
            }

            abort(404);
        }

        return $next($request);
    }
}

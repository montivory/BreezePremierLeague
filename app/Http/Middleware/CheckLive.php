<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;
use Log;

class CheckLive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(App::environment('production')) {
            $startdate = Carbon::parse(config('app.startdate'));
            $enddate   = Carbon::parse(config('app.enddate'));
            $now       = Carbon::now();
            if (!$now->between($startdate, $enddate)) {
                return redirect()->route('cover');
            }
        }
        return $next($request);
    }
}

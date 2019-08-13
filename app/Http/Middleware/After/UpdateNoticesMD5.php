<?php

namespace App\Http\Middleware\After;

use Cache;
use Closure;
use App\Models\Eloquent\Announcements;

class UpdateNoticesMD5
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
        $response = $next($request);

        $announcements = Announcements::fetch();
        Cache::put('notices_md5',md5(json_encode($announcements)));

        return $response;

    }
}

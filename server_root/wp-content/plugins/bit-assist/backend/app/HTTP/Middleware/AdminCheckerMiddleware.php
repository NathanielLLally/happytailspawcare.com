<?php

namespace BitApps\Assist\HTTP\Middleware;

use BitApps\Assist\Deps\BitApps\WPKit\Http\Response;
use BitApps\Assist\Deps\BitApps\WPKit\Utils\Capabilities;

final class AdminCheckerMiddleware
{
    public function handle()
    {
        if (!Capabilities::check('manage_options')) {
            return Response::error('Access Denied: Only administrators are allowed to make this request')->httpStatus(411);
        }

        return true;
    }
}

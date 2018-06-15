<?php
/**
 * Created by PhpStorm.
 * User: dex
 * Date: 003 3.3
 * Time: 13:59
 */
namespace App\Listeners;

use Illuminate\Http\Response;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TokenException
{
    public function subscribe($events)
    {
        $events->listen(
            'tymon.jwt.absent',
            'App\Listeners\TokenException@onTokenAbsent'
        );

        $events->listen(
            'tymon.jwt.expired',
            'App\Listeners\TokenException@onTokenExpired'
        );

        $events->listen(
            'tymon.jwt.invalid',
            'App\Listeners\TokenException@onTokenInvalid'
        );

        $events->listen(
            'tymon.jwt.user_not_found',
            'App\Listeners\TokenException@onUserNotFound'
        );
    }

    public function onTokenAbsent()
    {
        return response(['status' => Response::HTTP_UNAUTHORIZED, 'error' => '缺少Token'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function onTokenExpired()
    {
        return response(['status' => Response::HTTP_UNAUTHORIZED, 'error' => 'Token过期'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function onTokenInvalid()
    {
        return response(['status' => Response::HTTP_UNAUTHORIZED, 'error' => 'Token不合法'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function onUserNotFound()
    {
        return response(['status' => Response::HTTP_UNAUTHORIZED, 'error' => '用户不存在'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
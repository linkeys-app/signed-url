<?php

namespace Linkeys\LinkGenerator\Middleware;

use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Event;
use Linkeys\LinkGenerator\Contracts\Models\Link;
use Linkeys\LinkGenerator\Events\LinkClicked;
use Linkeys\LinkGenerator\Exceptions\ClickLimit\LinkClickLimitReachedException;
use Linkeys\LinkGenerator\Exceptions\ClickLimit\LinkGroupClickLimitReachedException;
use Linkeys\LinkGenerator\Exceptions\Expiry\LinkExpiredException;
use Linkeys\LinkGenerator\Exceptions\Expiry\LinkGroupExpiredException;
use Linkeys\LinkGenerator\Exceptions\LinkNotFoundException;
use Linkeys\LinkGenerator\Support\LinkRepository\LinkRepository;
use Symfony\Component\HttpFoundation\Request;

class CheckLinkValid
{

        public function handle(Request $request, Closure $next)
    {

        $link = $request->get('link');

        if($link->clickLimitReached()) {
            throw new LinkClickLimitReachedException;
        }
        if($link->group && $link->group->clickLimitReached()) {
            throw new LinkGroupClickLimitReachedException;
        }
        if($link->expired()) {
            throw new LinkExpiredException;
        }

        if($link->group && $link->group->expired() && $link->expiry === null) {
            throw new LinkGroupExpiredException;
        }

        Event::dispatch(new LinkClicked($link));

        return $next($request);
    }

}
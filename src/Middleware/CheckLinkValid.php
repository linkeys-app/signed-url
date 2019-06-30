<?php

namespace Linkeys\UrlSigner\Middleware;

use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Event;
use Linkeys\UrlSigner\Contracts\Models\Link;
use Linkeys\UrlSigner\Events\LinkClicked;
use Linkeys\UrlSigner\Exceptions\ClickLimit\LinkClickLimitReachedException;
use Linkeys\UrlSigner\Exceptions\ClickLimit\LinkGroupClickLimitReachedException;
use Linkeys\UrlSigner\Exceptions\Expiry\LinkExpiredException;
use Linkeys\UrlSigner\Exceptions\Expiry\LinkGroupExpiredException;
use Linkeys\UrlSigner\Exceptions\LinkNotFoundException;
use Linkeys\UrlSigner\Support\LinkRepository\LinkRepository;
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
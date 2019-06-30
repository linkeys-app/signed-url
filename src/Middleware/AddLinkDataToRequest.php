<?php


namespace Linkeys\UrlSigner\Middleware;


use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Linkeys\UrlSigner\Contracts\Models\Link;
use Linkeys\UrlSigner\Exceptions\LinkNotFoundException;
use Linkeys\UrlSigner\Support\LinkRepository\LinkRepository;
use Linkeys\UrlSigner\Support\UrlManipulator\UrlManipulator;
use Symfony\Component\HttpFoundation\Request;

class AddLinkDataToRequest
{


    public function handle(Request $request, Closure $next)
    {

        $link = $request->get('link');

        $request->attributes->add($link->data);

        return $next($request);
    }

}
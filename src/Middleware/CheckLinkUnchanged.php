<?php


namespace Linkeys\UrlSigner\Middleware;


use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Linkeys\UrlSigner\Contracts\Models\Link;
use Linkeys\UrlSigner\Exceptions\LinkNotFoundException;
use Linkeys\UrlSigner\Support\LinkRepository\LinkRepository;
use Linkeys\UrlSigner\Support\UrlManipulator\UrlManipulator;
use Symfony\Component\HttpFoundation\Request;

class CheckLinkUnchanged
{

    /**
     * @var UrlManipulator
     */
    private $urlManipulator;

    public function __construct(UrlManipulator $urlManipulator)
    {

        $this->urlManipulator = $urlManipulator;
    }

    public function handle(Request $request, Closure $next)
    {

        $link = $request->get('link');

        $this->urlManipulator->setUrl($request->getUri());
        $this->urlManipulator->removeQuery(config('links.query_key'));
        if($link->url !== $this->urlManipulator->getUrl()){
            throw new LinkNotFoundException;
        }
        return $next($request);
    }
}
<?php


namespace Linkeys\UrlSigner\Middleware;


use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Linkeys\UrlSigner\Contracts\Models\Link;
use Linkeys\UrlSigner\Exceptions\LinkNotFoundException;
use Linkeys\UrlSigner\Support\LinkRepository\LinkRepository;
use Linkeys\UrlSigner\Support\UrlManipulator\UrlManipulator;
use Symfony\Component\HttpFoundation\Request;

class AddLinkToRequest
{

    /**
     * @var LinkRepository
     */
    private $linkRepository;

    public function __construct(LinkRepository $linkRepository)
    {

        $this->linkRepository = $linkRepository;
    }

    public function handle(Request $request, Closure $next)
    {

        try {
            $link = $this->link($request);
        } catch (ModelNotFoundException $e) {
            throw new LinkNotFoundException;
        }

        $request->attributes->add(['link' => $link]);

        return $next($request);
    }

    /**
     * Find the link from the request
     *
     * @param Request $request
     *
     * @throws ModelNotFoundException
     *
     * @return Link
     */
    public function link(Request $request)
    {
        $uuid = $request->get(config('links.query_key'));

        return $this->linkRepository->findByUuid($uuid);

    }
}
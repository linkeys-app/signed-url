<?php

namespace Linkeys\UrlSigner\Tests\Integration\Middleware;

use Illuminate\Http\Request;
use Linkeys\UrlSigner\Exceptions\LinkNotFoundException;
use Linkeys\UrlSigner\Middleware\AddLinkToRequest;
use Linkeys\UrlSigner\Models\Link;
use Linkeys\UrlSigner\Support\LinkRepository\EloquentLinkRepository;
use Linkeys\UrlSigner\Support\UrlManipulator\SpatieUrlManipulator;
use Linkeys\UrlSigner\Tests\TestCase;

class AddLinkToRequestTest extends TestCase
{

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_merges_the_link_into_a_request_attribute(){
        $link = factory(Link::class)->create(['url' => 'https://www.example.com/invitation']);
        $request = new Request(
            [config('links.query_key') => $link->uuid],
            [],
            ['foo' => 'bar', Link::class => 'baz'],
            [],
            [],
            ['HTTPS'=>1, 'HTTP_HOST' => 'www.example.com', 'REQUEST_URI' => '/invitation']
        );

        $middleware = new AddLinkToRequest(new EloquentLinkRepository(new Link));
        $newRequest = $middleware->handle($request, function($request){
            return $request;
        });

        $this->assertTrue($link->is($newRequest->get(Link::class)));
        $this->assertEquals('bar', $newRequest->get('foo'));
    }

}
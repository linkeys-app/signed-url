<?php

namespace Linkeys\LinkGenerator\Tests\Integration\Middleware;

use Illuminate\Http\Request;
use Linkeys\LinkGenerator\Exceptions\LinkNotFoundException;
use Linkeys\LinkGenerator\Middleware\AddLinkToRequest;
use Linkeys\LinkGenerator\Models\Link;
use Linkeys\LinkGenerator\Support\LinkRepository\EloquentLinkRepository;
use Linkeys\LinkGenerator\Support\UrlManipulator\SpatieUrlManipulator;
use Linkeys\LinkGenerator\Tests\TestCase;

class AddLinkToRequestTest extends TestCase
{

    /** @test */
    public function it_merges_the_request_parameters(){
        $link = factory(Link::class)->create(['url' => 'https://www.example.com/invitation']);
        $request = new Request(
            [config('links.query_key') => $link->uuid],
            [],
            ['foo' => 'bar', 'link' => 'baz'],
            [],
            [],
            ['HTTPS'=>1, 'HTTP_HOST' => 'www.example.com', 'REQUEST_URI' => '/invitation']
        );




        $middleware = new AddLinkToRequest(new EloquentLinkRepository(new Link));
        $newRequest = $middleware->handle($request, function($request){
            return $request;
        });

        $this->assertTrue($link->is($newRequest->get('link')));
        $this->assertEquals('bar', $newRequest->get('foo'));
    }


}
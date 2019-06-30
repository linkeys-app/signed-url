<?php

namespace Linkeys\UrlSigner\Tests\Integration\Middleware;

use Illuminate\Http\Request;
use Linkeys\UrlSigner\Exceptions\LinkNotFoundException;
use Linkeys\UrlSigner\Middleware\AddLinkDataToRequest;
use Linkeys\UrlSigner\Middleware\AddLinkToRequest;
use Linkeys\UrlSigner\Models\Link;
use Linkeys\UrlSigner\Support\LinkRepository\EloquentLinkRepository;
use Linkeys\UrlSigner\Support\UrlManipulator\SpatieUrlManipulator;
use Linkeys\UrlSigner\Tests\TestCase;

class AddLinkDataToRequestTest extends TestCase
{
    /** @test */
    public function it_merges_link_data_into_a_request_attribute(){
        $link = factory(Link::class)->create(['url' => 'https://www.example.com/invitation', 'data' => ['foo' => 'bar']]);
        $request = new Request(
            [config('links.query_key') => $link->uuid],
            [],
            ['link' => $link, 'xyz' => 123],
            [],
            [],
            ['HTTPS'=>1, 'HTTP_HOST' => 'www.example.com', 'REQUEST_URI' => '/invitation']
        );

        $middleware = new AddLinkDataToRequest();
        $newRequest = $middleware->handle($request, function($request){
            return $request;
        });

        $this->assertEquals('bar', $newRequest->get('foo'));
        $this->assertEquals(123, $newRequest->get('xyz'));
    }

}
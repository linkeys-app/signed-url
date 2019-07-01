<?php


namespace Linkeys\UrlSigner\Tests\Integration\Middleware;


use Illuminate\Http\Request;
use Linkeys\UrlSigner\Exceptions\LinkNotFoundException;
use Linkeys\UrlSigner\Middleware\CheckLinkUnchanged;
use Linkeys\UrlSigner\Models\Link;
use Linkeys\UrlSigner\Support\LinkRepository\EloquentLinkRepository;
use Linkeys\UrlSigner\Support\UrlManipulator\SpatieUrlManipulator;
use Linkeys\UrlSigner\Tests\TestCase;

class CheckLinkUnchangedTest extends TestCase
{

    public function saveLinkInRequest($link){
        $request = $this->prophesize(\Symfony\Component\HttpFoundation\Request::class);
        $request->get(Link::class)->shouldBeCalled()->willReturn($link);
        return $request;
    }

    /** @test */
    public function it_throws_a_link_not_found_exception_if_the_url_is_different_to_that_in_the_database(){
        $link = factory(Link::class)->create(['url' => 'https://www.example.com/invitation?foo=bar']);

        $request = $this->saveLinkInRequest($link);
        $request->getUri()->willReturn($link->url.'&baz=invalid');

        $middleware = new CheckLinkUnchanged(new SpatieUrlManipulator);
        $this->expectException(LinkNotFoundException::class);
        $this->expectExceptionMessage('Invalid Link');
        $this->expectExceptionCode(404);

        $middleware->handle($request->reveal(), function($request){});

    }

    /** @test */
    public function it_passes_if_the_url_is_equal_to_the_link_url(){
        $link = factory(Link::class)->create(['url' => 'https://www.example.com/invitation?foo=bar']);

        $request = $this->saveLinkInRequest($link);
        $request->getUri()->willReturn($link->url);

        $middleware = new CheckLinkUnchanged(new SpatieUrlManipulator);

        $called = false;
        try {
            $middleware->handle($request->reveal(), function() use (&$called) {
                $called = true;
            });
        } catch(\Exception $e) {
            $this->assertTrue(false, 'Link Validation Middleware did not pass');
        }

        $this->assertTrue($called, 'Callback wasn\'t called');
    }
}
<?php

namespace Linkeys\LinkGenerator\Tests\Unit\Middleware;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Linkeys\LinkGenerator\Contracts\Models\Link;
use Linkeys\LinkGenerator\Exceptions\LinkNotFoundException;
use Linkeys\LinkGenerator\Middleware\AddLinkToRequest;
use Linkeys\LinkGenerator\Support\LinkRepository\LinkRepository;
use Linkeys\LinkGenerator\Support\UrlManipulator\UrlManipulator;
use Linkeys\LinkGenerator\Tests\TestCase;
use Symfony\Component\HttpFoundation\Request;

class AddLinkToRequestTest extends TestCase
{
    /** @test */
    public function it_retrieves_a_link_from_the_repository()
    {
        $uuid = '7575-dffd-23f3-sdda-23d3-grr4';
        $linkRepository = $this->prophesize(LinkRepository::class);
        $request = $this->prophesize(Request::class);
        $link = $this->prophesize(Link::class);

        $request->get(config('links.query_key'))->shouldBeCalled()->willReturn($uuid);
        $linkRepository->findByUuid($uuid)->shouldBeCalled()->willReturn($link->reveal());

        $middleware = new AddLinkToRequest($linkRepository->reveal());
        $foundLink = $middleware->link($request->reveal());

        $this->assertInstanceOf(Link::class, $foundLink);
    }

    /** @test */
    public function it_throws_a_link_not_found_exception_if_the_repository_throws_a_model_not_found_exception()
    {
        $uuid = '7575-dffd-23f3-sdda-23d3-grr4';
        $linkRepository = $this->prophesize(LinkRepository::class);
        $request = $this->prophesize(Request::class);

        $request->get(config('links.query_key'))->shouldBeCalled()->willReturn($uuid);
        $linkRepository->findByUuid($uuid)->shouldBeCalled()->will(function () {
            throw new ModelNotFoundException;
        });

        $this->expectException(LinkNotFoundException::class);
        $this->expectExceptionCode(404);
        $this->expectExceptionMessage('Invalid Link');

        $middleware = new AddLinkToRequest($linkRepository->reveal());
        $middleware->handle($request->reveal(), function () {
            return;
        });
    }

}
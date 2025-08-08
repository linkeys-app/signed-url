<?php

namespace Linkeys\UrlSigner\Tests\Unit;

use Linkeys\UrlSigner\Contracts\UrlSigner as UrlSignerContract;
use Linkeys\UrlSigner\Facade\UrlSigner as LinkFacade;
use Linkeys\UrlSigner\Tests\TestCase;

class LinkFacadeTest extends TestCase
{


    #[\PHPUnit\Framework\Attributes\Test]
    public function it_calls_the_generate_function(){

        $linkGenerator = $this->prophesize(UrlSignerContract::class);
        $linkGenerator->generate('url')->shouldBeCalled();
        $this->instance(UrlSignerContract::class, $linkGenerator->reveal());

        LinkFacade::generate('url');

    }
}

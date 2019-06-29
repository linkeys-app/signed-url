<?php

namespace Linkeys\LinkGenerator\Tests\Unit;

use Linkeys\LinkGenerator\Contracts\LinkGenerator as LinkGeneratorContract;
use Linkeys\LinkGenerator\Link as LinkFacade;
use Linkeys\LinkGenerator\Tests\TestCase;

class LinkFacadeTest extends TestCase
{


    /** @test */
    public function it_calls_the_generate_function(){

        $linkGenerator = $this->prophesize(LinkGeneratorContract::class);
        $linkGenerator->generate('url')->shouldBeCalled();
        $this->instance(LinkGeneratorContract::class, $linkGenerator->reveal());

        LinkFacade::generate('url');

    }
}
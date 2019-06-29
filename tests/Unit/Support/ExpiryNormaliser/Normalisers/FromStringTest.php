<?php

namespace Linkeys\LinkGenerator\Tests\Unit\Support\ExpiryNormaliser\Normalisers;

use Linkeys\LinkGenerator\Support\ExpiryNormaliser\Normalisers\FromString;
use Linkeys\LinkGenerator\Tests\TestCase;

class FromStringTest extends TestCase
{

    /** @test */
    public function it_converts_a_string_to_a_datetime(){
        $expiry = 'now';
        $fromString = new FromString();

        $this->assertInstanceOf(\DateTime::class, $fromString->normalise($expiry));
    }
}
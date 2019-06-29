<?php

namespace Linkeys\LinkGenerator\Tests\Unit\Support\ExpiryNormaliser\Normalisers;

use Carbon\Carbon;
use Linkeys\LinkGenerator\Support\ExpiryNormaliser\Normalisers\FromInteger;
use Linkeys\LinkGenerator\Tests\TestCase;

class FromIntegerTest extends TestCase
{

    /** @test */
    public function it_converts_an_integer_to_a_datetime(){
        $expiry = Carbon::now()->timestamp;
        $fromInteger = new FromInteger();

        $this->assertInstanceOf(\DateTime::class, $fromInteger->normalise($expiry));
    }
}
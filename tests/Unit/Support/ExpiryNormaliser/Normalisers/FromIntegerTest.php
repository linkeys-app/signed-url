<?php

namespace Linkeys\UrlSigner\Tests\Unit\Support\ExpiryNormaliser\Normalisers;

use Carbon\Carbon;
use Linkeys\UrlSigner\Support\ExpiryNormaliser\Normalisers\FromInteger;
use Linkeys\UrlSigner\Tests\TestCase;

class FromIntegerTest extends TestCase
{

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_converts_an_integer_to_a_datetime(){
        $expiry = Carbon::now()->timestamp;
        $fromInteger = new FromInteger();

        $this->assertInstanceOf(\DateTime::class, $fromInteger->normalise($expiry));
    }
}
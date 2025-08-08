<?php

namespace Linkeys\UrlSigner\Tests\Unit\Support\ExpiryNormaliser\Normalisers;

use Carbon\Carbon;
use Linkeys\UrlSigner\Support\ExpiryNormaliser\Normalisers\FromDateTime;
use Linkeys\UrlSigner\Tests\TestCase;

class FromDateTimeTest extends TestCase
{

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_converts_a_string_to_a_datetime(){
        $expiry = Carbon::now();
        $fromDateTime = new FromDateTime();

        $this->assertInstanceOf(\DateTime::class, $fromDateTime->normalise($expiry));
    }
}
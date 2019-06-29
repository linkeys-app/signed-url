<?php

namespace Linkeys\LinkGenerator\Tests\Unit\Support\ExpiryNormaliser\Normalisers;

use Carbon\Carbon;
use Linkeys\LinkGenerator\Support\ExpiryNormaliser\Normalisers\FromDateTime;
use Linkeys\LinkGenerator\Tests\TestCase;

class FromDateTimeTest extends TestCase
{

    /** @test */
    public function it_converts_a_string_to_a_datetime(){
        $expiry = Carbon::now();
        $fromDateTime = new FromDateTime();

        $this->assertInstanceOf(\DateTime::class, $fromDateTime->normalise($expiry));
    }
}
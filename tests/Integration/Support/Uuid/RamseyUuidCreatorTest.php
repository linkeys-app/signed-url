<?php

namespace Linkeys\UrlSigner\Tests\Integration\Support\Uuid;

use Linkeys\UrlSigner\Support\Uuid\RamseyUuidCreator;
use Linkeys\UrlSigner\Tests\TestCase;

class RamseyUuidCreatorTest extends TestCase
{

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_generates_a_uuid()
    {
        $uuidCreator = new RamseyUuidCreator;
        $uuid = $uuidCreator->create();

        $this->assertIsString($uuid);
        $this->assertEquals(36, strlen($uuid));
    }
    
}
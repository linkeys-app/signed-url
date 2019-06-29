<?php

namespace Linkeys\LinkGenerator\Tests\Integration\Support\Uuid;

use Linkeys\LinkGenerator\Support\Uuid\RamseyUuidCreator;
use Linkeys\LinkGenerator\Tests\TestCase;

class RamseyUuidCreatorTest extends TestCase
{

    /** @test */
    public function it_generates_a_uuid()
    {
        $uuidCreator = new RamseyUuidCreator;
        $uuid = $uuidCreator->create();

        $this->assertIsString($uuid);
        $this->assertEquals(36, strlen($uuid));
    }
    
}
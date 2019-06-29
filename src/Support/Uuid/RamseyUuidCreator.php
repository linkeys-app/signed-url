<?php

namespace Linkeys\LinkGenerator\Support\Uuid;

use Ramsey\Uuid\Uuid;

class RamseyUuidCreator implements UuidCreator
{

    public function create()
    {
        return Uuid::uuid4()->toString();
    }

}
<?php

namespace Linkeys\LinkGenerator\Support\ExpiryNormaliser;

use Linkeys\LinkGenerator\Support\ExpiryNormaliser\Normalisers\ExpiryNormaliser;

interface NormaliserManagerContract
{

    public function pushNormaliser(ExpiryNormaliser $normaliser);

    public function normalise($expiry);

}
<?php

namespace Linkeys\UrlSigner\Support\ExpiryNormaliser;

use Linkeys\UrlSigner\Support\ExpiryNormaliser\Normalisers\ExpiryNormaliser;

interface NormaliserManagerContract
{

    public function pushNormaliser(ExpiryNormaliser $normaliser);

    public function normalise($expiry);

}
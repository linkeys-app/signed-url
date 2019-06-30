<?php

namespace Linkeys\UrlSigner\Support\ExpiryNormaliser\Normalisers;

use Carbon\Carbon;
class FromString extends ExpiryNormaliser
{

    public function normalise($expiry)
    {
        if(is_string($expiry)) {
            return new Carbon($expiry);
        }

        return null;
    }
}
<?php

namespace Linkeys\LinkGenerator\Support\ExpiryNormaliser\Normalisers;

use Carbon\Carbon;
class FromInteger extends ExpiryNormaliser
{

    public function normalise($expiry)
    {
        if(is_int($expiry)) {
            return (new Carbon())->setTimestamp($expiry);
        }

        return null;
    }

}
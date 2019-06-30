<?php

namespace Linkeys\UrlSigner\Exceptions\Expiry;

use Throwable;

class LinkExpiredException extends ExpiredException
{

    public function __construct($message = "Link Expired", $code = 410, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
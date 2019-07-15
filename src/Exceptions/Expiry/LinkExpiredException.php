<?php

namespace Linkeys\UrlSigner\Exceptions\Expiry;

use Throwable;

class LinkExpiredException extends ExpiredException
{

    public function __construct($code = 410, $message = "Link Expired", Throwable $previous = null)
    {
        parent::__construct($code, $message, $previous);
    }
}
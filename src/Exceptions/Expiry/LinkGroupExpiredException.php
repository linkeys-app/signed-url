<?php

namespace Linkeys\UrlSigner\Exceptions\Expiry;

use Throwable;

class LinkGroupExpiredException extends ExpiredException
{

    public function __construct($message = "Link Group Expired", $code = 410, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
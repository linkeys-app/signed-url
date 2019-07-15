<?php

namespace Linkeys\UrlSigner\Exceptions\Expiry;

use Throwable;

class LinkGroupExpiredException extends ExpiredException
{

    public function __construct($code = 410, $message = "Link Group Expired", Throwable $previous = null)
    {
        parent::__construct($code, $message, $previous);
    }
}
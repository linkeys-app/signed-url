<?php

namespace Linkeys\UrlSigner\Exceptions\Expiry;

use Linkeys\UrlSigner\Exceptions\LinkInvalidException;
use Throwable;

class ExpiredException extends LinkInvalidException
{

    public function __construct($message = "Link Expired", $code = 410, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
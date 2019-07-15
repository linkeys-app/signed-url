<?php

namespace Linkeys\UrlSigner\Exceptions\Expiry;

use Linkeys\UrlSigner\Exceptions\LinkInvalidException;
use Throwable;

class ExpiredException extends LinkInvalidException
{

    public function __construct($code = 410, $message = "Link Expired", Throwable $previous = null)
    {
        parent::__construct($code, $message, $previous);
    }

}
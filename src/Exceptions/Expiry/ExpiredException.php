<?php

namespace Linkeys\LinkGenerator\Exceptions\Expiry;

use Linkeys\LinkGenerator\Exceptions\LinkInvalidException;
use Throwable;

class ExpiredException extends LinkInvalidException
{

    public function __construct($message = "Link Expired", $code = 410, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
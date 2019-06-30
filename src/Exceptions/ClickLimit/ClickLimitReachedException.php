<?php

namespace Linkeys\UrlSigner\Exceptions\ClickLimit;

use Linkeys\UrlSigner\Exceptions\LinkInvalidException;
use Throwable;

class ClickLimitReachedException extends LinkInvalidException
{

    public function __construct($message = "Link clicked too many times", $code = 410, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
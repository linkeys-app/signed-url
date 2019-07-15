<?php

namespace Linkeys\UrlSigner\Exceptions\ClickLimit;

use Linkeys\UrlSigner\Exceptions\LinkInvalidException;
use Throwable;

class ClickLimitReachedException extends LinkInvalidException
{

    public function __construct($code = 410, $message = "Link clicked too many times", Throwable $previous = null)
    {
        parent::__construct($code, $message, $previous);
    }

}
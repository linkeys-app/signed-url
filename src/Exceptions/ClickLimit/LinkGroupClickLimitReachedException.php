<?php

namespace Linkeys\UrlSigner\Exceptions\ClickLimit;

use Throwable;

class LinkGroupClickLimitReachedException extends ClickLimitReachedException
{

    public function __construct($code = 410, $message = "Link group clicked too many times", Throwable $previous = null)
    {
        parent::__construct($code, $message, $previous);
    }
}
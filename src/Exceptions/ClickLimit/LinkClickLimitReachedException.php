<?php

namespace Linkeys\UrlSigner\Exceptions\ClickLimit;

use Throwable;

class LinkClickLimitReachedException extends ClickLimitReachedException
{

    public function __construct($code = 410, $message = "Link clicked too many times", Throwable $previous = null)
    {
        parent::__construct($code, $message, $previous);
    }

}
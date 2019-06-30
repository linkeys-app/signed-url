<?php

namespace Linkeys\LinkGenerator\Exceptions\ClickLimit;

use Throwable;

class LinkGroupClickLimitReachedException extends ClickLimitReachedException
{

    public function __construct($message = "Link group clicked too many times", $code = 410, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
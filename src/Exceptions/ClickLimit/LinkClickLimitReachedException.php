<?php

namespace Linkeys\LinkGenerator\Exceptions\ClickLimit;

use Throwable;

class LinkClickLimitReachedException extends ClickLimitReachedException
{

    public function __construct($message = "Link clicked too many times", $code = 410, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
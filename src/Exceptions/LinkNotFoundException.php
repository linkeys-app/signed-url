<?php

namespace Linkeys\UrlSigner\Exceptions;

use Throwable;

class LinkNotFoundException extends \Exception
{
    public function __construct($message = "Invalid Link", $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
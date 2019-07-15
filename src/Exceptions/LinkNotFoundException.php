<?php

namespace Linkeys\UrlSigner\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class LinkNotFoundException extends HttpException
{
    public function __construct($code = 404, $message = "Invalid Link", Throwable $previous = null)
    {
        parent::__construct($code, $message, $previous);
    }
}
<?php


namespace Linkeys\UrlSigner\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class LinkInvalidException extends HttpException
{

    public function __construct($code = 410, $message = "Invalid Link", Throwable $previous = null)
    {
        parent::__construct($code, $message, $previous);
    }

}
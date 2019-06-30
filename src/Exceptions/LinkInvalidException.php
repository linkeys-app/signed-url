<?php


namespace Linkeys\UrlSigner\Exceptions;

use Exception;
use Throwable;

class LinkInvalidException extends Exception
{

    public function __construct($message = "Invalid Link", $code = 410, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
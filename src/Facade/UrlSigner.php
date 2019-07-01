<?php


namespace Linkeys\UrlSigner\Facade;


use Illuminate\Support\Facades\Facade;
use Linkeys\UrlSigner\Contracts\UrlSigner as UrlSignerContract;

class UrlSigner extends Facade
{

    protected static function getFacadeAccessor()
    {
        return UrlSignerContract::class;
    }

}
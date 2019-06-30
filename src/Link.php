<?php


namespace Linkeys\UrlSigner;


use Illuminate\Support\Facades\Facade;
use Linkeys\UrlSigner\Contracts\UrlSigner as UrlSignerContract;

class Link extends Facade
{

    protected static function getFacadeAccessor()
    {
        return UrlSignerContract::class;
    }

}
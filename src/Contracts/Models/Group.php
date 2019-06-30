<?php


namespace Linkeys\UrlSigner\Contracts\Models;


interface Group
{
    public function clickLimitReached();

    public function expired();

}
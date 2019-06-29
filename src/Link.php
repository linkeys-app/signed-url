<?php


namespace Linkeys\LinkGenerator;


use Illuminate\Support\Facades\Facade;
use Linkeys\LinkGenerator\Contracts\LinkGenerator as LinkGeneratorContract;

class Link extends Facade
{

    protected static function getFacadeAccessor()
    {
        return LinkGeneratorContract::class;
    }

}
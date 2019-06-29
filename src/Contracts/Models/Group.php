<?php


namespace Linkeys\LinkGenerator\Contracts\Models;


interface Group
{
    public function clickLimitReached();

    public function expired();

}
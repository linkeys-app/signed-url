<?php


namespace Linkeys\LinkGenerator\Contracts\Models;


interface Link
{
    public function getFullUrl();

    public function clickLimitReached();

    public function expired();
}
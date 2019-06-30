<?php

namespace Linkeys\UrlSigner\Listeners;

use Linkeys\UrlSigner\Contracts\Models\Link;
use Linkeys\UrlSigner\Events\LinkClicked;

class RecordLinkClick
{

    public function handle(LinkClicked $linkClicked)
    {
        $link = $linkClicked->link;
        $link->clicks++;
        $link->save();
    }

}
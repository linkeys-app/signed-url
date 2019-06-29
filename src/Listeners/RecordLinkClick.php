<?php

namespace Linkeys\LinkGenerator\Listeners;

use Linkeys\LinkGenerator\Contracts\Models\Link;
use Linkeys\LinkGenerator\Events\LinkClicked;

class RecordLinkClick
{

    public function handle(LinkClicked $linkClicked)
    {
        $link = $linkClicked->link;
        $link->clicks++;
        $link->save();
    }

}
<?php

namespace Linkeys\LinkGenerator\Events;

use Illuminate\Queue\SerializesModels;
use Linkeys\LinkGenerator\Contracts\Models\Link;

class LinkClicked
{
    use SerializesModels;

    public $link;

    public function __construct(Link $link)
    {
        $this->link = $link;
    }

}
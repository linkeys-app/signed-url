<?php

namespace Linkeys\UrlSigner\Events;

use Illuminate\Queue\SerializesModels;
use Linkeys\UrlSigner\Contracts\Models\Link;

class LinkClicked
{
    use SerializesModels;

    public $link;

    public function __construct(Link $link)
    {
        $this->link = $link;
    }

}
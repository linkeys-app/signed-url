<?php

namespace Linkeys\UrlSigner\Contracts;

use Linkeys\UrlSigner\Contracts\Models\Link;

interface UrlSigner
{

    public function generate(string $url, $expiry = null, $clickLimit = null) : Link;

}
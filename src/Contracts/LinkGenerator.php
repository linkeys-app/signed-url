<?php

namespace Linkeys\LinkGenerator\Contracts;

use Linkeys\LinkGenerator\Contracts\Models\Link;

interface LinkGenerator
{

    public function generate(string $url, $expiry = null, $clickLimit = null) : Link;

}
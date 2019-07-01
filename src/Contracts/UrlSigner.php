<?php

namespace Linkeys\UrlSigner\Contracts;

use Linkeys\UrlSigner\Contracts\Models\Link;

interface UrlSigner
{

    public function generate(string $url, $data = [], $expiry = null, $clickLimit = null): Link;

    public function sign(string $url, $data = [], $expiry = null, $clickLimit = null): Link;

    public function group(callable $callback, $expiry = null, $clickLimit = null);
}
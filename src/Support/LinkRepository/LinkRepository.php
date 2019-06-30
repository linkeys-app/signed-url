<?php

namespace Linkeys\UrlSigner\Support\LinkRepository;

use Linkeys\UrlSigner\Contracts\Models\Link;

interface LinkRepository
{

    public function findByUuid($uuid) : Link;

    public function save(Link $link);

    public function create($attributes);

}
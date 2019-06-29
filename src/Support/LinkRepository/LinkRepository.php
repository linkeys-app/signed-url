<?php

namespace Linkeys\LinkGenerator\Support\LinkRepository;

use Linkeys\LinkGenerator\Contracts\Models\Link;

interface LinkRepository
{

    public function findByUuid($uuid) : Link;

    public function save(Link $link);

    public function create($attributes);

}
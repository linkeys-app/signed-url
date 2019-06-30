<?php

namespace Linkeys\UrlSigner\Support\GroupRepository;

use Linkeys\UrlSigner\Contracts\Models\Link;
use Linkeys\UrlSigner\Models\Group;

interface GroupRepository
{

    public function create($attributes);

    public function pushLink(Group $group, Link $link);

}
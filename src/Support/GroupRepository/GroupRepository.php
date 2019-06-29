<?php

namespace Linkeys\LinkGenerator\Support\GroupRepository;

use Linkeys\LinkGenerator\Contracts\Models\Link;
use Linkeys\LinkGenerator\Models\Group;

interface GroupRepository
{

    public function create($attributes);

    public function pushLink(Group $group, Link $link);

}
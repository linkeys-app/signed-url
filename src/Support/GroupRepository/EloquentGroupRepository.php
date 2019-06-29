<?php

namespace Linkeys\LinkGenerator\Support\GroupRepository;

use Linkeys\LinkGenerator\Contracts\Models\Link;
use Linkeys\LinkGenerator\Models\Group;

class EloquentGroupRepository implements GroupRepository
{

    /**
     * @var Group
     */
    private $group;

    public function __construct(Group $group)
    {
        $this->group = $group;
    }

    public function pushLink(Group $group, Link $link)
    {
        $group->links()->save($link);
    }

    public function create($attributes)
    {
        return $this->group->create($attributes);
    }
}
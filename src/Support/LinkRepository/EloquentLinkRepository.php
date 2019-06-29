<?php

namespace Linkeys\LinkGenerator\Support\LinkRepository;

use Linkeys\LinkGenerator\Contracts\Models\Link as LinkContract;
use Linkeys\LinkGenerator\Models\Link;

class EloquentLinkRepository implements LinkRepository
{

    /**
     * @var Link
     */
    private $link;

    public function __construct(Link $link)
    {
        $this->link = $link;
    }

    public function findByUuid($uuid): LinkContract
    {
        return $this->link->where(['uuid' => $uuid])->firstOrFail();
    }

    public function save(LinkContract $link)
    {
        $link->save();
    }

    public function create($attributes)
    {
        return $this->link->create($attributes);
    }
}
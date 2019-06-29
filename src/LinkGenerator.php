<?php

namespace Linkeys\LinkGenerator;

use \DateTime;
use Linkeys\LinkGenerator\Contracts\LinkGenerator as LinkGeneratorContract;
use Linkeys\LinkGenerator\Contracts\Models\Link as LinkContract;
use Linkeys\LinkGenerator\Models\Link;
use Linkeys\LinkGenerator\Support\GroupRepository\GroupRepository;
use Linkeys\LinkGenerator\Support\LinkRepository\LinkRepository;
use Linkeys\LinkGenerator\Support\UrlManipulator\UrlManipulator;

class LinkGenerator implements LinkGeneratorContract
{
    protected $linkRepository;

    protected $urlParser;

    protected $queryParameter = 'uuid';

    private $group;
    /**
     * @var GroupRepository
     */
    private $groupRepository;

    public function __construct(LinkRepository $linkRepository, GroupRepository $groupRepository, UrlManipulator $urlParser)
    {
        $this->linkRepository = $linkRepository;
        $this->urlParser = $urlParser;
        $this->groupRepository = $groupRepository;
    }

    /**
     * Create a new link
     *
     * @param string $url
     * @param array|null $data
     * @param DateTime|int|string|null $expiry
     * @param null $clickLimit
     * @return Link
     * @throws \Exception
     */
    public function generate(string $url, $data = [], $expiry = null, $clickLimit = null) : LinkContract
    {
        $link = $this->linkRepository->create([
            'url' => $url,
            'data' => $data,
            'click_limit' => $clickLimit,
            'expiry' => $expiry
        ]);

        if($this->group !== null) {
            $this->groupRepository->pushLink($this->group, $link);
        }

        return $link;
    }

    public function group(callable $callback, $expiry = null, $clickLimit = null)
    {
        $group = $this->groupRepository->create([
            'expiry' => $expiry,
            'clickLimit' => $clickLimit
        ]);

        $this->group = $group;
        $callback($this);
        $this->group = null;

        return $group;
    }

}
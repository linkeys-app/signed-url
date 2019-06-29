<?php

namespace Linkeys\LinkGenerator\Support\UrlManipulator;

use http\QueryString;
use http\Url;
use Linkeys\LinkGenerator\Support\UrlManipulator\UrlManipulator as UrlManipulatorContract;

class httpUrlManipulator implements UrlManipulatorContract
{

    /**
     * @var Url
     */
    protected $url;

    public function setUrl(string $url)
    {
        $this->url = new Url($url);
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url->toString();
    }

    public function appendQuery(array $newQuery)
    {
        $query = new QueryString($this->url->query);
        $query->set($newQuery);
        $this->url->query = $query->toString();
    }

    public function getQuery() : ?array
    {
        $query = new QueryString($this->url->query);
        return $query->toArray();
    }

}
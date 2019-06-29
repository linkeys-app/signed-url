<?php

namespace Linkeys\LinkGenerator\Support\UrlManipulator;

use Linkeys\LinkGenerator\Support\UrlManipulator\UrlManipulator as UrlManipulatorContract;
use Spatie\Url\Url;

class SpatieUrlManipulator implements UrlManipulatorContract
{

    /**
     * @var Url
     */
    protected $url;

    public function setUrl(string $url)
    {
        $this->url = Url::fromString($url);
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return (string) $this->url;
    }

    public function appendQuery(array $newQuery)
    {
        foreach($newQuery as $key => $value) {
            $this->url = $this->url->withQueryParameter($key, $value);
        }
    }

    public function getQuery() : ?array
    {
        return $this->url->getAllQueryParameters();
    }

}
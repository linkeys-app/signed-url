<?php

namespace Linkeys\LinkGenerator\Support\UrlManipulator;

interface UrlManipulator
{

    public function setUrl(string $url);

    public function getUrl();

    public function appendQuery(array $query);

    public function getQuery() : ?array;

}
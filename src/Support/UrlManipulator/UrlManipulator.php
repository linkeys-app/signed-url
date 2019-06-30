<?php

namespace Linkeys\UrlSigner\Support\UrlManipulator;

interface UrlManipulator
{

    public function setUrl(string $url);

    public function getUrl();

    public function appendQuery(array $query);

    public function removeQuery($key);

    public function getQuery() : ?array;

}
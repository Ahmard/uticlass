<?php

namespace Uticlass;

use Queliwrap\Client;

/**
 * Class Importer
 * @package Uticlass
 * @method static Importer import(string $url) Import remote file
 */
class Importer
{
    protected string $url;

    public static function __callStatic($method, $args)
    {
        $method = "_{$method}";
        return (new self())->$method(...$args);
    }

    public function _import($url): Importer
    {
        $this->url = $url;
        return $this;
    }

    public function save($file): bool
    {
        Client::get($this->url)->sink($file)->exec();

        return true;
    }

}
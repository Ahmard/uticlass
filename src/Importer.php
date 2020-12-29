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

    public static function __callStatic(string $method, array $args): Importer
    {
        $method = "_{$method}";
        return (new Importer())->$method(...$args);
    }

    public function _import(string $url): Importer
    {
        $this->url = $url;
        return $this;
    }

    public function save(string $file): bool
    {
        Client::get($this->url)->sink($file)->execute();

        return true;
    }

}
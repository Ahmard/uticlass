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
        if ('import' == $method){
            return new Importer(...$args);
        }

        $className = __CLASS__;
        throw new \Exception("Method {$className}::{$method}() does not exists.");
    }

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function save(string $file): bool
    {
        Client::get($this->url)->sink($file)->execute();

        return true;
    }

}
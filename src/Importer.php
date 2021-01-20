<?php

namespace Uticlass;

use Guzwrap\Request;
use Guzwrap\RequestInterface;
use Queliwrap\Client;
use Uticlass\Core\Scraper;

/**
 * Class Importer
 * @package Uticlass
 * @method static Importer import(string $url) Import remote file
 */
class Importer extends Scraper
{
    public static function __callStatic(string $method, array $args): Importer
    {
        if ('import' == $method){
            return new Importer(...$args);
        }

        $className = __CLASS__;
        throw new \Exception("Method {$className}::{$method}() does not exists.");
    }

    public function save(string $file): void
    {
        if (isset($this->request)){
            Request::useRequest($this->request)
                ->get($this->url)
                ->sink($file)
                ->exec();
        }else{
            Request::get($this->url)->sink($file)->exec();
        }
    }

}
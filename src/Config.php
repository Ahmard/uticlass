<?php


namespace Uticlass;


use Guzwrap\Core\Cookie;
use GuzzleHttp\Cookie\CookieJar;

class Config
{
    use Cookie;

    private CookieJar $cookieJar;

    public static function new(): self
    {
        return new self();
    }

    public function getCookieJar(): CookieJar
    {
        if (!isset($this->cookieJar)){
            $this->cookieJar = new CookieJar();
        }

        return $this->cookieJar;
    }
}
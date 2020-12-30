<?php

namespace Uticlass\Video;

use Queliwrap\Client;
use Uticlass\Core\Scraper;
use Uticlass\Core\Struct\Traits\InstanceCreator;

class FPO extends Scraper
{
    public function get(): array
    {
        $ql = Client::get($this->url)->execute();
        $downloadLinks = null;

        $html = $ql->getHtml();
        $pattern = "#0/https://[^\s]+mp4#";
        preg_match_all($pattern, $html, $output);

        $downloadLinks = array_map(function ($link) {
            return str_replace('0/', '', $link);
        }, $output);

        return $downloadLinks;
    }
}
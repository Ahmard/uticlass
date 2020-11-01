<?php

namespace Uticlass\Video;

use Queliwrap\Client;
use Uticlass\Core\Struct\Traits\InstanceCreator;

class FPO
{
    use InstanceCreator;

    public function get()
    {
        $ql = Client::get($this->url)->exec();
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
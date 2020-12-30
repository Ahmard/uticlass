<?php

namespace Uticlass\Video\FEMkvCom;

use Queliwrap\Client;
use Uticlass\Core\Scraper;

class Episode extends Scraper
{
    protected static array $links;

    public static function getLinks(string $href): array
    {
        self::$links = [];

        Client::get($href)->execute()
            ->find('p > a')
            ->each(function ($node) {
                self::$links[] = [
                    'name' => $node->text(),
                    'href' => $node->attr('href')
                ];
            });

        return self::$links;
    }
}

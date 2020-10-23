<?php

namespace Uticlass\Video\FEMkvCom;

use Queliwrap\Client;

class Episode
{
    protected static $links;

    public static function getLinks($href)
    {
        self::$links = [];
        
        Client::request(function ($gr) use ($href) {
            $gr->get($href);
        })->then(function ($ql) {
            $ql->find('p > a')
                ->each(function ($node) {
                    self::$links[] = [
                        'name' => $node->text(),
                        'href' => $node->attr('href')
                    ];
                });
        });
        
        return self::$links;
    }
}

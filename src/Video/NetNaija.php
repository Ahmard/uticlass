<?php

namespace Uticlass\Video;

use Queliwrap\Client;
use Uticlass\Core\Struct\Traits\InstanceCreator;

/**
 * Extract download link from https://netnaija.com
 */
class NetNaija
{
    use InstanceCreator;

    protected array $firstLinks = [];

    public function get()
    {
        Client::get($this->url)->exec()
            ->find('.button.download')
            ->each(function ($node) {
                $this->firstLinks[] = $node->attr('href');
            });

        return $this;
    }

    public function linkTwo($url = null)
    {
        $url ??= $this->firstLinks[1];
        $dlLink = null;

        $ql = Client::get($url)->exec();
        $dlLink = $ql->find('form')
            ->eq(1)
            ->find('input')->eq(3)
            ->attr('value');

        return $dlLink;
    }
}
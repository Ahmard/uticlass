<?php


namespace Uticlass\Video;


use Queliwrap\Client;
use Uticlass\Core\Struct\Traits\InstanceCreator;

class CoolMoviez
{
    use InstanceCreator;

    public function get()
    {
        $pageLink1 = $this->pageOne();
        $pageLink2 = $this->pageTwo($pageLink1);
        $downloadLink = $this->pageThree($pageLink2);

        return [
            'referrer' => $pageLink2,
            'link' => $downloadLink,
        ];
    }

    public function pageOne()
    {
        return Client::get($this->url)->exec()
            ->find('html body div.list div.fl a.fileName')
            ->attr('href');
    }

    public function pageTwo(string $url)
    {
        return Client::get($url)->exec()
            ->find('html body div.list div.fshow div.updates a.dwnLink')
            ->attr('href');
    }

    public function pageThree(string $url)
    {
        return Client::get($url)->exec()
            ->find('html body div.list div.fshow div.downLink a.dwnLink')
            ->attr('href');
    }
}
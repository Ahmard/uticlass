<?php

namespace Uticlass\Video;

use Goutte\Client;
use Uticlass\Core\Struct\Traits\InstanceCreator;

class FZMovies
{
    use InstanceCreator {
        __construct as ICConstructor;
    }

    protected string $links;

    protected ?string $downloadLink;

    protected int $downloadOption = 1;

    public function __construct(string $url)
    {
        $this->ICConstructor($url);
        $this->extractLinks();
    }

    private function extractLinks()
    {
        $client = new Client;

        $crawler = $client->request('GET', $this->url);
        $linkTextOne = $crawler->filter('#downloadoptionslink2')->text();
        $linkOne = $crawler->selectLink($linkTextOne)->link();

        $crawlerTwo = $client->click($linkOne);
        $linkTextTwo = $crawlerTwo->filter('#downloadlink')->text();
        $linkTwo = $crawlerTwo->selectLink($linkTextTwo)->link();

        $crawlerThree = $client->click($linkTwo);
        $linkTextThree = $crawlerThree->filter("#dlink{$this->downloadOption}")->text();
        $linkThree = $crawlerThree->selectLink($linkTextThree)->link();

        $crawlerFour = $client->click($linkThree);
        $this->downloadLink = $crawlerFour->filter('a')->eq(0)->attr('href');
    }

    public function get($format = 'mp4')
    {
        return $this->downloadLink;
    }
}
<?php

namespace Uticlass\Video;

use Goutte\Client;
use Uticlass\Core\Scraper;
use Uticlass\Core\Struct\Traits\InstanceCreator;

class FZMovies extends Scraper
{
    protected string $links;

    protected ?string $downloadLink;

    public function __construct(string $url)
    {
        parent::__construct($url);
    }

    public function get(int $chosenLink = 1): string
    {
        $client = new Client;

        $crawler = $client->request('GET', $this->url);
        $linkTextOne = $crawler->filter('#downloadoptionslink2')->text();
        $linkOne = $crawler->selectLink($linkTextOne)->link();

        $crawlerTwo = $client->click($linkOne);
        $linkTextTwo = $crawlerTwo->filter('#downloadlink')->text();
        $linkTwo = $crawlerTwo->selectLink($linkTextTwo)->link();

        $crawlerThree = $client->click($linkTwo);
        $linkTextThree = $crawlerThree->filter("#dlink{$chosenLink}")->text();
        $linkThree = $crawlerThree->selectLink($linkTextThree)->link();

        $crawlerFour = $client->click($linkThree);
        $this->downloadLink = $crawlerFour->filter('a')->eq(0)->attr('href');

        return $this->downloadLink;
    }
}
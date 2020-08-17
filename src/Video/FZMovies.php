<?php
namespace Uticlass\Video;

//use Queliwrap\Client;
use Goutte\Client;

class FZMovies
{
    protected $url;
    
    protected $links;
    
    protected $downloadLink;
    
    protected $downloadOption = 1;
    
    
    public function __construct($url)
    {
        $this->url = $url;
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
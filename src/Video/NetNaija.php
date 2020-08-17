<?php
namespace Uticlass\Video;

use Queliwrap\Client;

/**
 * Extract download link from https://netnaija.com
 */
class NetNaija
{
    protected $url;
    
    protected $firstLinks = [];
    
    public function __construct($url)
    {
        $this->url = $url;
    }
    
    
    public function get()
    {
        Client::request(function($gr){
            $gr->get($this->url);
        })->then(function($ql){
            $ql->find('.button.download')->each(function($node){
                $this->firstLinks[] = $node->attr('href');
            });
        });
        
        return $this;
    }
    
    public function linkTwo($url = null)
    {
        $url ??= $this->firstLinks[1];
        $dlLink = null;
        Client::request(function($gr) use ($url){
            $gr->get($url);
        })->then(function($ql) use(&$dlLink){
            $dlLink = $ql->find('form')->eq(1)
                ->find('input')->eq(3)
                ->attr('value');
        });
        
        return $dlLink;
    }
}
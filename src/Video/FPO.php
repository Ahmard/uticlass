<?php
namespace Uticlass\Video;

use Queliwrap\Client;

class FPO
{
    protected $url;
    
    
    public function __construct($url)
    {
        $this->url = $url;
    }
    
    public function get() 
    {
        Client::request(function($g){
            $g->get($this->url);
        })->then(function($ql){
            $html = $ql->getHtml();
            $pattern = "#0/https://[^\s]+mp4#";
            preg_match_all($pattern, $html, $output);
    
            $downloadLinks = array_map(function($link) {
                return str_replace('0/', '', $link);
            }, $output);
        });

        return $downloadLinks;
    }
}
<?php
namespace Uticlass\Others;

use Queliwrap\Client;

class ZippyShare
{
    protected $url;
    
    
    public function __construct(string $url)
    {
        $this->url = $url;
    }
    
    public function get()
    {
        Client::request(function($g){
            $g->get($this->url);
        })->then(function($ql){
            $script = $ql->find('script:eq(9)')->html();
            $script = explode('=', $script)[1];
            $script = explode(';', $script)[0];
            dd($script);
        });
    }
    
}
<?php
namespace Uticlass\News;

use Uticlass\Struct\Abstracts\NewsAbstract;
use Queliwrap\Client;

class BBCHausa extends NewsAbstract
{
    protected $websiteUrl;
    
    protected $newsList = array();
    
    protected $error = null;
    
    
    public function __construct()
    {
        $this->websiteUrl = 'https://bbc.com/hausa';
    }
    
    
    public function fetch() : object
    {
        $client = Client::get($this->websiteUrl);
        
        $client->then(function($ql){
            $ql->find("li")->each(function($li){
                //Link and text
                $a = $li->find('h3')->find('a');
                $text = trim($a->text());
                $href = $this->makeUrl(trim($a->attr('href')));
                //Summary
                $summary = $li->find('p')->text();
                //Time
                $time = $li->find('time')->text();
                
                if($text){
                    $this->newsList[] = [
                        'href' => $href,
                        'text' => $text,
                        'summary' => $summary,
                        'time' => $time
                    ];
                }
            });
        });
        
        $client->otherwise(function($err){
            $this->error = $err;
        });
        
        return $this;
    }
    
}
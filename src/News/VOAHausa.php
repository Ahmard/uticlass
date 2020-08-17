<?php
namespace Uticlass\News;

use Queliwrap\Client;
use Uticlass\Struct\Abstracts\NewsAbstract;

class VOAHausa extends NewsAbstract
{
    protected $websiteUrl;
    
    protected $newsList = array();
    
    protected $error = null;
    

    public function __construct()
    {
        $this->websiteUrl = 'https://www.voahausa.com/';
    }
        
    public function fetch() : object
    {
        $client = Client::get($this->websiteUrl);
        
        $client->then(function($ql){
            $ql->find('div.media-block__content')->each(function($li){
                //Link and text
                $a = $li->find('a');
                $text = trim($a->find('h4')->eq(0)->text());
                $href = $this->makeUrl($a->attr('href'));
                //Time
                $time = trim($li->find('span')->eq(0)->text());
                if($text && $time){
                    $this->newsList[] = [
                        'text' => $text,
                        'href' => $href,
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
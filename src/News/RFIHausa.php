<?php
namespace Uticlass\News;

use Uticlass\Struct\Abstracts\NewsAbstract;
use Queliwrap\Client;

class RFIHausa extends NewsAbstract
{
    protected $websiteUrl;
    
    protected $newsList = array();
    
    protected $error = null;
    
    
    public function __construct()
    {
        $this->websiteUrl = 'http://www.rfi.fr/ha/';
    }
    
    public function fetch() : object
    {
        $client = Client::get($this->websiteUrl);
        
        $client->then(function($ql){
            $ql->find('.m-item-list-article')->each(function($div){
                $this->newsList[] = [
                    'text' => $div->find('p:eq(0)')->text(),
                    'href' => $this->makeUrl($div->find('a:eq(0)')->attr('href'))
                ];
            });
        });
        
        $client->otherwise(function($err){
            $this->error = $err;
        });
        
        return $this;
    }
    
}

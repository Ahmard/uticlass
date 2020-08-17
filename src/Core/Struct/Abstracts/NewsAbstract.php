<?php
namespace App\Struct\Abstracts;

use App\Utils\News\NewsFactory;
use App\Struct\Interfaces\NewsInterface;

abstract class NewsAbstract implements NewsInterface
{
    public function getAll() : array
    {
        return $this->newsList;
    }
    
    
    public function getNews(\integer $newsKey) : NewsFactory
    {
        return new NewsFactory($this->newsList[$newsKey] ?? []);
    }
    
    
    public function getWebsiteUrl() : string
    {
        return $this->websiteUrl;
    }
    
    
    public function getError()
    {
        return $this->error;
    }
    
    
    public function makeUrl(string $url) : string
    {
        if(! strpos($url, 'http://') || strpos($url, 'https://')){
            return $this->websiteUrl . $url;
        }
        
        return $url;
    }
}
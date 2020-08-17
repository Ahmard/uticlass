<?php
namespace Uticlass;

use Uticlass\News\{
    BBCHausa,
    RFIHausa,
    DWHausa,
    VOAHausa,
    AllSites
};

class News
{
    protected static $methods = [
        'bbchausa' => BBCHausa::class, 
        'rfihausa' => RFIHausa::class, 
        'dwhausa' => DWHausa::class,
        'voahausa' => VOAHausa::class
    ];
    
    
    public static function __callStatic($method, $arguments)
    {
        //If we are fetching news from all sites
        if($method == 'allSites'){
            return new AllSites();
        }
        
        if(array_key_exists($method, static::$methods)){
            return new static::$methods[$method];
        }
    }
    
    
    public static function getSites()
    {
        return static::$methods;
    }
}
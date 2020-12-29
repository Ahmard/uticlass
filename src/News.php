<?php
namespace Uticlass;

use Uticlass\News\{
    BBCHausa,
    RFIHausa,
    DWHausa,
    VOAHausa,
    AllSites
};
use Uticlass\Core\Struct\Interfaces\NewsInterface;

class News
{
    protected static array $methods = [
        'bbchausa' => BBCHausa::class, 
        'rfihausa' => RFIHausa::class, 
        'dwhausa' => DWHausa::class,
        'voahausa' => VOAHausa::class
    ];
    
    
    public static function __callStatic(string $method, array $arguments): object
    {
        //If we are fetching news from all sites
        if($method == 'allSites'){
            return new AllSites();
        }
        
        if(array_key_exists($method, static::$methods)){
            return new static::$methods[$method];
        }

        throw new \Exception("New platform '{$method}' is not available..");
    }
    
    
    public static function getSites(): array
    {
        return static::$methods;
    }
}
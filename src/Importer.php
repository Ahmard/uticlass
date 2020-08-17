<?php
namespace Uticlass;

//use Queliwrap\Client;
use GuzzleHttp\Client;

class Importer
{
    protected $url;
    
    
    public static function __callStatic($method, $args)
    {
        $method = "_{$method}";
        return (new static())->$method(...$args);
    }
    
    
    public function _import($url)
    {
        $this->url = $url;
        return $this;
    }
    
    
    public function save($file)
    {
        $client = new Client();
        $client->request('GET', $this->url, [
            'sink' => $file,
        ]);
        
        return true;
    }
    
}
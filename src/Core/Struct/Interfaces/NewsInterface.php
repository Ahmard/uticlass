<?php
namespace App\Struct\Interfaces;

use App\Utils\News\NewsFactory;

interface NewsInterface
{

    public function getWebsiteUrl() : string;


    public function getAll() : array;
    
    
    public function getNews(\integer $newsKey) : NewsFactory;
    
    
    public function fetch() : object;

}
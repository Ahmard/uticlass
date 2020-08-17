<?php
namespace Uticlass\News;

use Uticlass\News;

class AllSites
{
    public function fetch()
    {
        $newsList = array();
        foreach (News::getSites() as $site => $siteClass){
            $newsList[$site] = (new $siteClass)->fetch()->getAll();
        }
        
        return $newsList;
    }
}
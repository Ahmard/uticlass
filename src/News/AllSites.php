<?php

namespace Uticlass\News;

use Uticlass\News;

class AllSites
{
    public function fetch(): array
    {
        $newsList = array();
        foreach (News::getSites() as $site => $siteClass) {
            $newsList[$site] = (new $siteClass)->fetch()->getAll();
        }

        return $newsList;
    }
}
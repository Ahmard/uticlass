<?php

use Uticlass\Video\Search\NetNaijaSearch;

require dirname(__DIR__, 1) . '/vendor/autoload.php';

$url = 'https://www.thenetnaija.com/search?t=star+wars';

$results = NetNaijaSearch::create()
    ->search('love')
    ->category(NetNaijaSearch::CAT_MOVIES)
    ->get(3);

var_dump($results);
<?php

use Uticlass\Video\Search\FZMoviesSearch;

require 'vendor/autoload.php';

$searchResult = FZMoviesSearch::create()
    ->search('wrong')
    ->get(2);
    
dump($searchResult);
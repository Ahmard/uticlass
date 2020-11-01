<?php
use Uticlass\Video\FZMovies;

require(dirname(__DIR__, 3) . '/autoload.php');

$url = 'https://fzmovies.net/movie-Moana%202016--hmp4.htm';
$dlLink = FZMovies::init($url)->get();

var_dump($dlLink);
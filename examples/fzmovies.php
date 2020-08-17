<?php
use Uticlass\Video\FZMovies;

require(dirname(__DIR__, 1) . '/vendor/autoload.php');

$url = 'https://fzmovies.net/movie-Moana%202016--hmp4.htm';
$dlLink = (new FZMovies($url))->get();

var_dump($dlLink);
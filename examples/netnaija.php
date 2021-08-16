<?php
use Uticlass\Video\NetNaija;

require(dirname(__DIR__) . '/vendor/autoload.php');

$url = 'https://www.thenetnaija.com/videos/movies/6128-the-beyond-2017/';
$dlLink = NetNaija::init($url)->get();

var_dump($dlLink);
<?php
use Uticlass\Video\NetNaija;

require(dirname(__DIR__, 1) . '/vendor/autoload.php');

$url = 'https://www.thenetnaija.com/videos/movies/6128-the-beyond-2017';
$dlLink = (new NetNaija($url))->get()->linkTwo();

var_dump($dlLink);
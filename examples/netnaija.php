<?php
use Uticlass\Video\NetNaija;

require(dirname(__DIR__, 3) . '/autoload.php');

$url = 'https://www.thenetnaija.com/videos/movies/6128-the-beyond-2017';
$dlLink = NetNaija::init($url)->get()->linkTwo();

var_dump($dlLink);
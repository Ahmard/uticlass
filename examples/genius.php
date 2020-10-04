<?php

require(dirname(__DIR__, 1) . '/vendor/autoload.php');

use Uticlass\Lyrics\Genius;

$lyrics = Genius::get('http://localhost:8000/lgad.html');

var_dump($lyrics);
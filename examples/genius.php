<?php

require(dirname(__DIR__, 3) . '/autoload.php');

use Uticlass\Lyrics\Genius;

$lyrics = Genius::init('http://localhost:8000/lgad.html')->get();

var_dump($lyrics);
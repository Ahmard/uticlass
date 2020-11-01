<?php

use Uticlass\Lyrics\Genius;
use Uticlass\Editors\Audio;

require(dirname(__DIR__, 3) . '/autoload.php');

$lyrics = Genius::init('https://genius.com/Taylor-swift-the-last-great-american-dynasty-lyrics')->get();

$editor = new Audio('Taylor_Swift_-_the_last_great_american_dynasty.mp3');
$editor->setLyric(trim($lyrics));
$editor->setTag('title', date('H:i:s'));

var_dump($editor->save());

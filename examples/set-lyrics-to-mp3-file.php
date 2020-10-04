<?php

use Uticlass\Lyrics\Genius;
use Uticlass\Editors\Audio;

require('vendor/autoload.php');

$lyrics = Genius::get('https://genius.com/Taylor-swift-the-last-great-american-dynasty-lyrics');

$editor = new Audio('Taylor_Swift_-_the_last_great_american_dynasty.mp3');
$editor->setLyric(trim($lyrics));
$editor->setTag('title', date('H:i:s'));

var_dump($editor->save());

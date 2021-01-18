<?php

use Uticlass\Others\FireFiles;

require '../vendor/autoload.php';

$webpageUrl = 'https://firefiles.org/5m6rzmnb7v54'; //The Mandalorian S01E06

$fileLink = FireFiles::init($webpageUrl)->get();
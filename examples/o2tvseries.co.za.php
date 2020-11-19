<?php

use Uticlass\Video\O2TVSeriesCoZa;

require 'vendor/autoload.php';

$o2tvseries = O2TVSeriesCoZa::init('https://o2tvseries.co/id/3554/fear-the-walking-dead.html');
$seasons = $o2tvseries->getLinks();

$results = [];
foreach ($seasons as $season) {
    $episodes = $o2tvseries->getEpisodes($season['href']);
    $episodeLinks = [];
    foreach ($episodes as $episode) {
        $episodeLinks[] = [
            'name' => $episode['name'],
            'href' => $episode['href'],
            'links' => $o2tvseries->getDownloadLinks($episode['href']),
        ];
    }

    $results[] = [
        'name' => $season['name'],
        'href' => $season['href'],
        'episodes' => $episodeLinks,
    ];
}

file_put_contents('results.json', json_encode($results, JSON_PRETTY_PRINT));

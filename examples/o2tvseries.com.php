<?php

use Uticlass\Video\O2TVSeriesCom;

require(dirname(__DIR__, 3) . '/autoload.php');

$o2tvseries = O2TVSeriesCom::init('https://o2tvseries.com/The-Crown-8/index.html');
$seasons = $o2tvseries->getLinks();
$results = [];
foreach ($seasons as $season){
    $episodes = $o2tvseries->getEpisodes($season['href']);
    $episodeLinks = [];
    foreach ($episodes as $episode){
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

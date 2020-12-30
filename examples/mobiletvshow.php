<?php

use Guzwrap\Request;
use Uticlass\Video\MobileTvShows;

require 'vendor/autoload.php';

$url = 'http://www.mobiletvshows.net/subfolder-Greys%20Anatomy.htm';

$request = Request::getInstance()->withCookie();

//Get number of seasons
$seasons = MobileTvShows::create()
    ->useRequest($request)
    ->getSeasons($url);

//Get total episodes on season
$episodes = MobileTvShows::create()
    ->useRequest($request)
    ->getEpisodes($seasons[14]['href']);

//Get stream/download url
$episodeLinks = MobileTvShows::create()
    ->useRequest($request)
    ->getEpisodeLinks($episodes[1]['links'][0]['href']);

//Get download links
$downloadLinks = MobileTvShows::create()
    ->useRequest($request)
    ->getDownloadLinks($episodeLinks['download']);
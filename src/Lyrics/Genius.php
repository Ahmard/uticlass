<?php

namespace Uticlass\Lyrics;

use Queliwrap\Client;

/**
 * Lyrics from https//genius.com
 * @package Uticlass\Lyrics
 */
class Genius
{
    /**
     * Get lyrics
     * @param string $url
     * @return string|\Throwable
     */
    public static function get(string $url)
    {
        $theLyrics = null;
        Client::request(function ($gz) use($url){
            $gz->get($url);
        })->then(function($ql) use(&$theLyrics){
            $lyrics = $ql->find('.lyrics')->html();
            $theLyrics = strip_tags($lyrics);
        })->otherwise(function(\Throwable $exception) use (&$theLyrics){
            $theLyrics = $exception;
        });
        
        return $theLyrics;
    }
}
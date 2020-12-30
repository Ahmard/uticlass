<?php

namespace Uticlass\Lyrics;

use Queliwrap\Client;
use Throwable;
use Uticlass\Core\Struct\Traits\InstanceCreator;

/**
 * Lyrics from https//genius.com
 * @package Uticlass\Lyrics
 */
class Genius
{
    use InstanceCreator;

    /**
     * Get lyrics
     * @return string|Throwable
     * @throws Throwable
     */
    public function get()
    {
        $theLyrics = null;

        $lyrics = Client::get($this->url)->execute()
            ->find('.lyrics')
            ->html();

        $theLyrics = strip_tags($lyrics);

        return $theLyrics;
    }
}
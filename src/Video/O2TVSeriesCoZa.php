<?php


namespace Uticlass\Video;


use QL\Dom\Elements;
use Queliwrap\Client;
use Throwable;
use Uticlass\Core\Scraper;
use Uticlass\Core\Struct\Traits\InstanceCreator;

class O2TVSeriesCoZa extends Scraper
{
    private string $domain;

    /**
     * Get season links
     * @return array
     * @throws Throwable
     */
    public function getLinks(): array
    {
        $seasons = [];
        $parsed = parse_url($this->url);
        $this->domain = "{$parsed['scheme']}://{$parsed['host']}";

        Client::get($this->url)->execute()
            ->find('ul[data-role="listview"]')
            ->eq(0)
            ->find('li')
            ->each(function (Elements $element) use (&$seasons) {
                $a = $element->find('a');
                $seasons[] = [
                    'name' => $a->find('h3')->text(),
                    'href' => $this->domain . $a->attr('href'),
                ];
            });

        return $seasons;
    }

    /**
     * Get season episodes
     * @param string $seasonUrl
     * @return array
     * @throws Throwable
     */
    public function getEpisodes(string $seasonUrl): array
    {
        $episodes = [];
        Client::get($seasonUrl)->execute()
            ->find('ul[data-role="listview"]')
            ->eq(0)
            ->find('li')
            ->each(function (Elements $element) use (&$episodes){
                $a = $element->find('a');
                $episodes[] = [
                    'name' => $a->find('h3')->text(),
                    'size' => $a->find('p')->text(),
                    'href' => $this->domain . $a->attr('href'),
                ];
            });

        return $episodes;
    }

    /**
     * Get episode download links
     * @param string $episodeUrl
     * @return array
     * @throws Throwable
     */
    public function getDownloadLinks(string $episodeUrl): array
    {

        $links = [];
        Client::get($episodeUrl)->execute()
            ->find('ul[data-role="listview"]')
            ->eq(0)
            ->find('li')
            ->each(function (Elements $element) use (&$links){
                $a = $element->find('a');
                $links[] = [
                    'name' => $a->find('h3')->text(),
                    'href' => $this->domain . $a->attr('href'),
                ];
            });

        return $links;
    }

    /**
     * Check if given url is collection url, example: The Blacklist complete
     * @param string $url
     * @return bool
     */
    public static function isCollectionUrl(string $url): bool
    {
        preg_match("@/id/([0-9]+)/@", $url, $matches);
        return count($matches) > 1;
    }

    /**
     * Check if given url is season url, example: The Blacklist season 1
     * @param string $url
     * @return bool
     */
    public static function isSeasonUrl(string $url): bool
    {
        preg_match("@/tv-series/([0-9]+)/@", $url, $matches);
        return count($matches) > 1;
    }
}
<?php


namespace Uticlass\Video;


use QL\Dom\Elements;
use Queliwrap\Client;
use Throwable;
use Uticlass\Core\Struct\Traits\InstanceCreator;

class O2TVSeriesCoZa
{
    use InstanceCreator;

    private string $domain;

    /**
     * Get season links
     * @return array[]
     * @throws Throwable
     */
    public function getLinks()
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
     * @return array[]
     * @throws Throwable
     */
    public function getEpisodes(string $seasonUrl)
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
     * @return array[]
     * @throws Throwable
     */
    public function getDownloadLinks(string $episodeUrl)
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
}
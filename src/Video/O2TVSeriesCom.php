<?php


namespace Uticlass\Video;


use QL\Dom\Elements;
use Queliwrap\Client;
use Throwable;
use Uticlass\Core\Struct\Traits\InstanceCreator;

class O2TVSeriesCom
{
    use InstanceCreator;

    /**
     * Get season links
     * @return array[]
     * @throws Throwable
     */
    public function getLinks()
    {
        $firstLinks = [];
        Client::get($this->url) ->exec()
            ->find('html body div.container div.data_list div.data')
            ->each(function (Elements $element) use (&$firstLinks){
                $firstLinks[] = [
                    'name' => $element->find('a')->text(),
                    'href' => $element->find('a')->attr('href')
                ];
            });

        return $firstLinks;
    }

    /**
     * Get season episodes
     * @param string $seasonUrl
     * @return array[]
     * @throws Throwable
     */
    public function getEpisodes(string $seasonUrl)
    {
        $secondLinks = [];
        Client::get($seasonUrl)->exec()
            ->find('html body div.container div.data_list div.data')
            ->each(function (Elements $element) use (&$secondLinks){
                $secondLinks[] = [
                    'name' => $element->find('a')->text(),
                    'href' => $element->find('a')->attr('href')
                ];
            });

        return $secondLinks;
    }

    /**
     * Get episode download links
     * @param string $episodeUrl
     * @return array[]
     * @throws Throwable
     */
    public function getDownloadLinks(string $episodeUrl)
    {
        $thirdLinks = [];
        Client::get($episodeUrl)->exec()
            ->find('html body div.container div.data_list')
            ->each(function (Elements $element) use (&$thirdLinks){
                $downloads = $element->find('span.count')->text();
                if (! empty($downloads)){
                    $thirdLinks[] = [
                        'name' => $element->find('a')->text(),
                        'href' => $element->find('a')->attr('href'),
                        'downloads' => $downloads,
                    ];
                }
            });

        return $thirdLinks;
    }
}
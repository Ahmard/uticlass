<?php


namespace Uticlass\Video;


use QL\Dom\Elements;
use Queliwrap\Client;
use Throwable;
use Uticlass\Core\Scraper;

class MobileTvShows extends Scraper
{
    private string $mtsHost = 'https://mobiletvshows.net/';

    /**
     * Get list of series
     * @param string $url
     * @return array
     * @throws Throwable
     */
    public function getSeasons(string $url): array
    {
        $seasons = [];
        Client::get($url)
            ->useRequestData($this->request->getRequestData())
            ->execute()
            ->find('div.mainbox3')
            ->each(function (Elements $element) use (&$seasons) {
                $link = $element->find('a');
                $seasons[] = [
                    'title' => $link->text(),
                    'href' => $this->mtsHost . $link->attr('href'),
                ];
            });

        return $seasons;
    }

    /**
     * Get list of episodes in series
     * @param string $seasonUrl
     * @return array
     * @throws Throwable
     */
    public function getEpisodes(string $seasonUrl): array
    {
        $episodes = [];
        $ql = Client::get($seasonUrl)
            ->useRequestData($this->request->getRequestData())
            ->execute();
        $ql->find('div.mainbox')
            ->each(function (Elements $element) use (&$episodes) {
                $tableDatum = $element->find('table > tr > td');
                $td2 = $tableDatum->eq(1);
                $image = $tableDatum->eq(0)->find('img')->attr('src');
                //Links
                $firstLink = $td2->find('span > a')->eq(0);
                $secondLink = $td2->find('span > a')->eq(1);
                $episodes[] = [
                    'image' => $this->mtsHost . $image,
                    'title' => $td2->find('span > small > b')->text(),
                    'links' => [
                        [
                            'title' => $firstLink->text(),
                            'href' => $this->mtsHost . $firstLink->attr('href')
                        ],
                        [
                            'title' => $secondLink->text(),
                            'href' => $this->mtsHost . $secondLink->attr('href')
                        ]
                    ],
                ];
            });

        return $episodes;
    }

    /**
     * Get stream/download links
     * @param string $episodeUrl
     * @return string[]
     * @throws Throwable
     */
    public function getEpisodeLinks(string $episodeUrl): array
    {
        $queryList = Client::get($episodeUrl)
            ->useRequestData($this->request->getRequestData())
            ->execute();

        return [
            'stream' => $this->mtsHost . $queryList->find('#slink1')->attr('href'),
            'download' => $this->mtsHost . $queryList->find('#dlink2')->attr('href'),
        ];
    }

    /**
     * Get episode download links
     * @param string $downloadUrl
     * @return array
     * @throws Throwable
     */
    public function getDownloadLinks(string $downloadUrl): array
    {
        $links = [];
        Client::get($downloadUrl)
            ->useRequestData($this->request->getRequestData())
            ->execute()
            ->find('input[name="filelink"]')
            ->each(function (Elements $element) use (&$links){
                $links[] = $element->attr('value');
            });
        return $links;
    }

    /**
     * Check if given url is season url, example: The Blacklist complete
     * @param string $url
     * @return bool
     */
    public static function isCollectionUrl(string $url): bool
    {
        return false !== strpos($url, 'subfolder-');
    }

    /**
     * Check if given url is season url, example: The Blacklist season 1
     * @param string $url
     * @return bool
     */
    public static function isSeasonUrl(string $url): bool
    {
        return false !== strpos($url, 'files-');
    }
}
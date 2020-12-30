<?php


namespace Uticlass\Video\Search;


use Nette\Utils\Strings;
use Queliwrap\Client;
use Uticlass\Core\Struct\Traits\InstanceCreator;

class MobileTVShowsSearch
{
    use InstanceCreator;


    private string $query;

    private string $searchBy = 'series';

    public const SEARCH_BY_SERIES = 'series';
    public const SEARCH_BY_EPISODE = 'episodes';


    private string $mtsHost = 'https://mobiletvshows.net/';

    private string $urlTemplate = 'search.php?search={query}&by={searchBy}&pg={pageNumber}';


    public function search(string $query): MobileTVShowsSearch
    {
        $this->query = $query;
        return $this;
    }

    /**
     * Search by Series or Episode
     * @param string $searchBy
     * @return $this
     */
    public function searchBy(string $searchBy): MobileTVShowsSearch
    {
        $this->searchBy = $searchBy;
        return $this;
    }

    public function get(int $pageNumber = 1): array
    {
        $urlTemplate = $this->urlTemplate;
        $url = $this->mtsHost . str_replace(
            [
                '{query}',
                '{searchBy}',
                '{pageNumber}',
            ], [
                $this->query,
                $this->searchBy,
                $pageNumber,
            ]
            , $urlTemplate
        );

        $queryList = Client::get($url)->execute();
        $searchResults = [];
        $queryList->find('div[class="mainbox"]')
            ->each(function ($div) use (&$searchResults) {
                $firstTd = $div->find('td')->eq(0);
                $secondTd = $div->find('td')->eq(1);
                $firstLink = $firstTd->find('a');

                $title = Strings::fixEncoding($secondTd->find('small')->eq(0)->text());
                $movieHref = Strings::fixEncoding($this->mtsHost . $firstLink->attr('href'));
                $movieImage = Strings::fixEncoding($this->mtsHost . $firstLink->find('img')->attr('src'));
                $movieDesc = Strings::fixEncoding(trim(strip_tags($secondTd->html())));

                $searchResults[] = [
                    'title' => $title,
                    'href' => $movieHref,
                    'image' => $movieImage,
                    'desc' => $movieDesc
                ];
            });

        $totalPages = $queryList
            ->find('html body div.mainbox2 small div form')
            ->text();
        preg_match("@([0-9]+)@", $totalPages, $matches);
        $totalPages = (int)($matches[0] ?? 0);

        return [
            'meta' => [
                'page_number' => $pageNumber,
                'total_pages' => $totalPages,
            ],
            'results' => $searchResults,
        ];
    }
}
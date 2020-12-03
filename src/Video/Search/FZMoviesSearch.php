<?php

namespace Uticlass\Video\Search;

use Nette\Utils\Strings;
use Queliwrap\Client;
use Throwable;
use Uticlass\Core\Struct\Traits\InstanceCreator;

class FZMoviesSearch
{
    use InstanceCreator;

    private string $query;

    private string $searchIn = 'All';

    private string $searchBy = 'Name';
    
    private string $fzHost = 'https://fzmovies.net/';

    private string $urlTemplate = 'csearch.php?searchname={query}&searchby={searchBy}&category={searchIn}&pg={pageNumber}';

    public const SEARCH_IN_ALL = 'All';
    public const SEARCH_IN_HOLLYWOOD = 'Hollywood';
    public const SEARCH_IN_BOLLYWOOD = 'Bollywood';
    public const SEARCH_IN_DUBBED = 'DHollywood';

    public const SEARCH_BY_NAME = 'Name';
    public const SEARCH_BY_DIRECTOR = 'Director';
    public const SEARCH_BY_STARCAST = 'Starcast';

    public function search(string $query): FZMoviesSearch
    {
        $this->query = $query;
        return $this;
    }

    /**
     * Category to perform searching in,
     * leave empty to search in all categories
     * @param string $category
     * @return $this
     */
    public function searchIn(string $category): FZMoviesSearch
    {
        $this->searchIn = $category;
        return $this;
    }


    /**
     * Search by Starcast, Director or Movie name
     * @return $this
     * @param  string $query
     */
    public function searchBy(string $query): FZMoviesSearch
    {
        $this->searchBy = $query;
        return $this;
    }

    /**
     * Perform the search
     * @throws Throwable
     * @param  int $pageNumber Navigate through search results
     * @return array
     */
    public function get(int $pageNumber = 1): array
    {
        $url = $this->fzHost . $this->urlTemplate;
        $url = str_replace('{query}', $this->query, $url);
        $url = str_replace('{searchIn}', $this->searchIn, $url);
        $url = str_replace('{searchBy}', $this->searchBy, $url);
        $url = str_replace('{pageNumber}', $pageNumber, $url);

        $searchResults = [];

        $dom = Client::get($url)->exec();

        $dom->find('div[class="mainbox"]')
            ->each(function ($div) use (&$searchResults) {
                $firstTd = $div->find('td')->eq(0);
                $secondTd = $div->find('td')->eq(1);
                $firstLink = $firstTd->find('a');

                $title = Strings::fixEncoding($secondTd->find('small')->eq(0)->text());
                $movieHref = Strings::fixEncoding($this->fzHost . $firstLink->attr('href'));
                $movieImage = Strings::fixEncoding($this->fzHost . $firstLink->find('img')->attr('src'));
                $movieDesc = Strings::fixEncoding(trim(strip_tags($secondTd->html())));

                $searchResults[] = [
                    'title' => $title,
                    'href' => $movieHref,
                    'image' => $movieImage,
                    'desc' => $movieDesc
                ];
            });

        $totalPages = $dom
            ->find('html body div.mainbox2 small div form')
            ->text();
        preg_match("@([0-9]+)@", $totalPages, $matches);
        $totalPages = (int)$matches[0] ?? 0;


        return [
            'meta' => [
                'page_number' => $pageNumber,
                'total_pages' => $totalPages,
            ],
            'results' => $searchResults,
        ];
    }
}
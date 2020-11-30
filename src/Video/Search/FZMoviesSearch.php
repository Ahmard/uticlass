<?php

namespace Uticlass\Video\Search;

use Uticlass\Core\Struct\Traits\InstanceCreator;
use Queliwrap\Client;

class FZMoviesSearch
{
    use InstanceCreator;
    
    private string $query;
    
    private string $searchIn = 'All';
    
    private string $searchBy = 'Name';
    
    private string $urlTemplate = 'https://fzmovies.net/csearch.php?searchname={query}&searchby={searchBy}&category={searchIn}&pg={pageNumber}';
    
    public const SEARCH_IN_ALL = 'All';
    public const SEARCH_IN_HOLLYWOOD = 'Hollywood';
    public const SEARCH_IN_BOLLYWOOD = 'Bollywood';
    public const SEARCH_IN_DUBBED = 'DHollywood';
    
    public const SEARCH_BY_NAME = 'Name';
    public const SEARCH_BY_DIRECTOR = 'Director';
    public const SEARCH_BY_STARCAST = 'Starcast';
    
    public function search(string $query)
    {
        $this->query = $query;
        return $this;
    }
    
    /**
     * Category to perform searching in,
     * leave empty to search in all categories
     * @var string $query
     * @return $this
     */
    public function searchIn(string $category): self
    {
        $this->searchIn = $category;
        return $this;
    }
    
    
    /**
     * Search by Starcast, Director or Movie name
     * @var string $query
     * @return $this
     */
    public function searchBy(string $query): self
    {
        $this->searchBy = $query;
        return $this;
    }
    
    /**
     * Perform the search
     * @var int $pageNumber Navigate through search resultz
     * @return array
     */
    public function get(int $pageNumber = 1): array
    {
        $url = str_replace('{query}', $this->query, $this->urlTemplate);
        $url = str_replace('{searchIn}', $this->searchIn, $url);
        $url = str_replace('{searchBy}', $this->searchBy, $url);
        $url = str_replace('{pageNumber}', $pageNumber, $url);
        
        $searchResults = [];
        
        Client::get($url)->exec()
            ->find('div[class="mainbox"]')
            ->each(function($div) use(&$searchResults){
                $firstTd = $div->find('td')->eq(0);
                $secondTd = $div->find('td')->eq(1);
                $firstLink = $firstTd->find('a');
                
                $movieHref = $firstLink->attr('href');
                $movieImage = $firstLink->find('img')->attr('src');
                $movieDesc = trim(strip_tags($secondTd->html()));
                
                $searchResults[] = [
                    'href' => $movieHref,
                    'image' => $movieImage,
                    'desc' => $movieDesc
                ];
            });
            
        return $searchResults;
    }
}
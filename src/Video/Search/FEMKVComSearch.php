<?php

namespace Uticlass\Video\Search;

use Uticlass\Core\Struct\Traits\InstanceCreator;
use Queliwrap\Client;

class FEMKVComSearch
{
    use InstanceCreator;
    
    private string $query;
    
    private string $urlTemplate = 'https://480mkv.com/page/{pageNumber}/?s={query}';
    
    public function search(string $query)
    {
        $this->query = $query;
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
        $url = str_replace('{pageNumber}', $pageNumber, $url);
        
        $searchResults = [];
        
        Client::get($url)->exec()
            ->find('article')
            ->each(function($article) use(&$searchResults){
                $firstLink = $article->find('a')->eq(0);
                $secondLink = $article->find('a')->eq(1);
                
                $movieHref = $firstLink->attr('href');
                $movieImage = $firstLink->find('img')->attr('src');
                $movieDesc = trim(strip_tags($article->find('p')->html()));
                
                $searchResults[] = [
                    'title' => $secondLink->text(),
                    'href' => $movieHref,
                    'image' => $movieImage,
                    'desc' => $movieDesc
                ];
            });
            
        return $searchResults;
    }
}
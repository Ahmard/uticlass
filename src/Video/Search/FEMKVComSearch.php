<?php

namespace Uticlass\Video\Search;

use Nette\Utils\Strings;
use QL\Dom\Elements;
use Queliwrap\Client;
use Throwable;
use Uticlass\Core\Struct\Traits\InstanceCreator;

class FEMKVComSearch
{
    use InstanceCreator;

    private string $query;

    private string $urlTemplate = 'https://480mkv.com/page/{pageNumber}/?s={query}';

    public function search(string $query): FEMKVComSearch
    {
        $this->query = $query;
        return $this;
    }

    /**
     * Perform the search
     * @return array
     * @throws Throwable
     * @var int $pageNumber Navigate through search results
     */
    public function get(int $pageNumber = 1): array
    {
        $url = str_replace('{query}', $this->query, $this->urlTemplate);
        $url = str_replace('{pageNumber}', (string)$pageNumber, $url);

        $searchResults = [];

        $dom = Client::get($url)->execute();

        $dom->find('article')
            ->each(function ($article) use (&$searchResults) {
                $firstLink = $article->find('a')->eq(0);
                $secondLink = $article->find('a')->eq(1);

                $movieHref = $firstLink->attr('href');
                $movieImage = $firstLink->find('img')->attr('src');
                $movieDesc = Strings::fixEncoding(trim(strip_tags($article->find('p')->html())));

                $searchResults[] = [
                    'title' => Strings::fixEncoding($secondLink->text()),
                    'href' => $movieHref,
                    'image' => $movieImage,
                    'desc' => $movieDesc
                ];
            });

        $totalPages = [];
        $dom->find('ul.page-numbers li')
            ->each(function (Elements $element) use (&$totalPages) {
                if ('' !== $element->text() && '…' !== $element->text()) {
                    $totalPages[] = Strings::fixEncoding($element->text());
                }
            });

        return [
            'meta' => [
                'page_number' => $pageNumber,
                'total_pages' => $totalPages,
            ],
            'results' => $searchResults,
        ];
    }
}
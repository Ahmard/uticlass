<?php


namespace Uticlass\Video\Search;


use QL\Dom\Elements;
use Queliwrap\Client;
use Throwable;
use Uticlass\Core\Searcher;

class NetNaijaSearch extends Searcher
{
    public const CAT_EVERYWHERE = '';
    public const CAT_FORUM = 'forum';
    public const CAT_MUSIC = 'music';
    public const CAT_VIDEOS = 'videos';
    public const CAT_MOVIES = 'movies';

    protected string $url = 'https://www.thenetnaija.com/search';
    protected array $params = [
        't' => '{query}',
        'folder' => '{category}',
        'page' => '{pageNumber}',
    ];

    /**
     * @param int $pageNumber
     * @return array
     * @throws Throwable
     */
    public function get(int $pageNumber = 1): array
    {
        $searchResults = [];
        //If movies is chosen, then we will scrape videos page and filter out movies
        $isMoviesCategory = false;
        if (self::CAT_MOVIES == $this->paramValues['{category}']) {
            $isMoviesCategory = true;
            $this->paramValues['{category}'] = self::CAT_VIDEOS;
        }

        $queryList = Client::get($this->getConstructedUrl($pageNumber))->execute();
        $queryList->find('div.search-results')
            ->find('article.sr-one')
            ->each(function (Elements $element) use (&$searchResults, $isMoviesCategory) {
                #dump($element->html());

                $infoElement = $element->find('div.info h3');
                $title = trim($infoElement->text());

                if ($isMoviesCategory && false !== strpos($title, 'Movie')) {
                    $image = $element->find('div.result-img img')->attr('src');
                    $link = $infoElement->find('a')->attr('href');
                    $desc = $element->find('p.result-desc')->text();
                    $searchResults[] = [
                        'title' => $title,
                        'href' => $link,
                        'image' => $image,
                        'desc' => $desc
                    ];
                }
            });

        //Pagination
        $pagingText = $queryList->find('div.pages div')->eq(1)->text();
        preg_match("@Viewing Page ([0-9]+) of ([0-9]+) pages@", $pagingText, $matches);

        return [
            'meta' => [
                'page_number' => $pageNumber,
                # 'total_pages' => (int)$matches[2],
                'total_pages' => -1, # Total pages is currently not supported
            ],
            'results' => $searchResults,
        ];
    }
}
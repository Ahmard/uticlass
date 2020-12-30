<?php


namespace Uticlass\Video;

use QL\Dom\Elements;
use Queliwrap\Client;
use Uticlass\Core\Scraper;
use Uticlass\Core\Struct\Traits\InstanceCreator;

class EgyBest extends Scraper
{
    private array $links = [];

    public function get(): EgyBest
    {
        Client::get($this->url)->execute()
            ->find('table.dls_table.btns.full.mgb tr')
            ->each(function (Elements $element) {
                $apiUrl = $element->find('td')
                    ->eq(3)
                    ->find('a')
                    ->attr('data-url');

                if ($apiUrl) {
                    $this->links[] = [
                        'encoding' => $element->find('td')
                            ->eq(0)
                            ->text(),
                        'quality' => $element->find('td')
                            ->eq(1)
                            ->text(),
                        'size' => $element->find('td')
                            ->eq(2)
                            ->text(),
                        'api_url' => $apiUrl
                    ];
                }
            });

        $this->getActualUrl();

        return $this;
    }

    private function getActualUrl(): void
    {
        for ($i = 0; $i < count($this->links); $i++) {
            Client::get($this->links[$i]['api_url'])
                ->header([
                    'Content-Type' => 'application/json'
                ])->execute();
        }
    }

    ///api?call=lgggcKLIgcgzgcSPgSbzMSSnczcgclAGgcgzkrLdfcgcgsSgcgzgcKSBUdFkNKKcKjdBZgIwLGBFczcgcILncgzgcwLnLILFCkzZgczcgcBwHbKLlBFcgzSPbSlzSPcSbSlbSXcgcKLygcgzgcSPlnXKztZxZgSnczcgcrngcgzgczKzrKGzKSaMbgcgb&auth=3650ad0c10f15f5d0184be2e99cc3507&v=1

    /**
     * Choose link with specific quality
     * @param string $quality
     * @return array
     */
    public function chooseQuality(string $quality): array
    {
        foreach ($this->links as $link) {
            if (strtolower($link['quality']) === strtolower($quality)) {
                return $link;
            }
        }

        return [];
    }

    /**
     * Get movie links
     * @return array
     */
    public function getLinks(): array
    {
        return $this->links;
    }
}
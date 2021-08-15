<?php


namespace Uticlass\Core;


class Searcher extends Scraper
{
    protected string $query;
    protected array $paramValues = [];
    protected array $params = [];

    public function search(string $query): Searcher
    {
        $this->paramValues['{query}'] = $query;
        return $this;
    }

    public function category(string $categoryName): Searcher
    {
        $this->paramValues['{category}'] = $categoryName;
        return $this;
    }

    protected function hasParam(string $param): bool
    {
        return array_key_exists($param, $this->paramValues);
    }

    protected function getConstructedUrl(int $pageNumber = 1): string
    {
        $this->paramValues['{pageNumber}'] = $pageNumber;
        $builtUrl = urldecode(http_build_query($this->params));

        return $this->url . '?' . str_replace(
            array_values($this->params),
            array_values($this->paramValues),
            $builtUrl
        );
    }
}
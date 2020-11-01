<?php

namespace Uticlass\Core\Struct\Abstracts;

use Uticlass\Core\Struct\Interfaces\NewsInterface;

abstract class NewsAbstract implements NewsInterface
{
    public function getAll(): array
    {
        return $this->newsList;
    }


    public function getNews(int $newsKey): array
    {
        return $this->newsList[$newsKey] ?? [];
    }


    public function getWebsiteUrl(): string
    {
        return $this->url;
    }


    public function getError()
    {
        return $this->error;
    }


    public function makeUrl(string $url): string
    {
        if (!strpos($url, 'http://') || strpos($url, 'https://')) {
            return $this->url . $url;
        }

        return $url;
    }
}
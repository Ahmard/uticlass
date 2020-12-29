<?php

namespace Uticlass\Core\Struct\Abstracts;

use Throwable;
use Uticlass\Core\Struct\Interfaces\NewsInterface;

abstract class NewsAbstract implements NewsInterface
{
    protected array $newsList = array();
    protected string $url;
    protected ?Throwable $error = null;

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


    public function getError(): ?Throwable
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
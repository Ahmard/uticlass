<?php

namespace Uticlass\Core\Struct\Interfaces;
use Uticlass\Core\Struct\Abstracts\NewsAbstract;

interface NewsInterface
{

    public function getWebsiteUrl(): string;


    public function getAll(): array;


    public function getNews(int $newsKey): array;


    public function fetch(): object;

}
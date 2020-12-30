<?php

namespace Uticlass\News;

use Queliwrap\Client;
use Throwable;
use Uticlass\Core\Struct\Abstracts\NewsAbstract;
use Uticlass\Core\Struct\Traits\InstanceCreator;

class RFIHausa extends NewsAbstract
{
    use InstanceCreator {
        __construct as ICConstructor;
    }

    protected array $newsList = array();

    protected ?Throwable $error = null;


    public function fetch(): object
    {
        try {
            Client::get($this->url)->execute()
                ->find('.m-item-list-article')
                ->each(function ($div) {
                    $this->newsList[] = [
                        'text' => $div->find('p:eq(0)')->text(),
                        'href' => $this->makeUrl($div->find('a:eq(0)')->attr('href'))
                    ];
                });
        } catch (Throwable $exception) {
            $this->error = $exception;
        }

        return $this;
    }

}

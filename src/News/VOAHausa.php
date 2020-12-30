<?php

namespace Uticlass\News;

use Queliwrap\Client;
use Throwable;
use Uticlass\Core\Struct\Abstracts\NewsAbstract;
use Uticlass\Core\Struct\Traits\InstanceCreator;

class VOAHausa extends NewsAbstract
{
    use InstanceCreator {
        __construct as ICConstruct;
    }

    protected array $newsList = array();

    protected ?Throwable $error = null;


    public function fetch(): object
    {
        try {
            Client::get($this->url)->execute()
                ->find('div.media-block__content')
                ->each(function ($li) {
                    //Link and text
                    $a = $li->find('a');
                    $text = trim($a->find('h4')->eq(0)->text());
                    $href = $this->makeUrl($a->attr('href'));
                    //Time
                    $time = trim($li->find('span')->eq(0)->text());
                    if ($text && $time) {
                        $this->newsList[] = [
                            'text' => $text,
                            'href' => $href,
                            'time' => $time
                        ];
                    }
                });
        } catch (Throwable $exception) {
            $this->error = $exception;
        }

        return $this;
    }
}
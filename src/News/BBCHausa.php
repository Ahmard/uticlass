<?php

namespace Uticlass\News;

use Queliwrap\Client;
use Throwable;
use Uticlass\Core\Struct\Abstracts\NewsAbstract;
use Uticlass\Core\Struct\Traits\InstanceCreator;

class BBCHausa extends NewsAbstract
{
    protected array $newsList = array();

    protected ?Throwable $error = null;

    public function fetch(): object
    {
        try {
            $client = Client::get($this->url)->execute()
                ->find("li")
                ->each(function ($li) {
                    //Link and text
                    $a = $li->find('h3')->find('a');
                    $text = trim($a->text());
                    $href = $this->makeUrl(trim($a->attr('href')));
                    //Summary
                    $summary = $li->find('p')->text();
                    //Time
                    $time = $li->find('time')->text();

                    if ($text) {
                        $this->newsList[] = [
                            'href' => $href,
                            'text' => $text,
                            'summary' => $summary,
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
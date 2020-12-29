<?php

namespace Uticlass\Video\FEMkvCom;

use Queliwrap\Client;
use Uticlass\Core\Struct\Traits\InstanceCreator;

class Main
{
    use InstanceCreator;

    protected array $episodes = array();

    protected array $links = array();

    public function get(): array
    {
        Client::get($this->url)->execute()
            ->find('#content')
            ->find('ol')->eq(0)
            ->find('li')
            ->each(function ($li) {
                $a = $li->find('a');

                if ($a->is('a')) {
                    $this->episodes[] = [
                        'name' => explode(' – ', $li->text())[0],
                        'href' => $a->attr('href'),
                        'links' => Episode::getLinks($a->attr('href'))
                    ];
                }
            });

        return $this->episodes;
    }

    public function save(string $path): void
    {
        Saver::save($this->episodes, $this->url, $path);
    }
}

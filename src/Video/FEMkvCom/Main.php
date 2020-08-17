<?php

namespace Uticlass\Video\FEMkvCom;

use Queliwrap\Client;

class Main
{
    protected $request;

    public $error;

    protected string $url;

    protected array $episodes = array();

    protected array $links = array();


    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function get(callable $callback)
    {
        Client::request(function ($gr) {
            $gr->get($this->url);
            //$gr->get('http://localhost/ahmard/amutils/storage/temp/1-another-life.html');
        })->then(function ($ql) {
            $ql->find('#content')
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
        });

        if ($callback) $callback($this->episodes);

        return $this;
    }

    public function save(string $path)
    {
        Saver::save($this->episodes, $this->url, $path);
    }
}

<?php


namespace Uticlass\Video;


use Exception;
use Guzwrap\Request;
use Psr\Http\Message\ResponseInterface;
use Queliwrap\Client;
use Uticlass\Core\Struct\Traits\InstanceCreator;

class CoolMoviez
{
    use InstanceCreator;

    public function get()
    {
        $pageLink1 = $this->pageOne();
        $pageLink2 = $this->pageTwo($pageLink1);
        $downloadLink = $this->pageThree($pageLink2);

        return $this->getFinalLink($downloadLink, $pageLink2);
    }

    public function pageOne()
    {
        return Client::get($this->url)->exec()
            ->find('html body div.list div.fl a.fileName')
            ->attr('href');
    }

    public function pageTwo(string $url)
    {
        return Client::get($url)->exec()
            ->find('html body div.list div.fshow div.updates a.dwnLink')
            ->attr('href');
    }

    public function pageThree(string $url)
    {
        return Client::get($url)->exec()
            ->find('html body div.list div.fshow div.downLink a.dwnLink')
            ->attr('href');
    }

    public function getFinalLink(string $url, string $referer)
    {
        $downloadLink = null;

        try {
            Request::get('https://www.coolmoviez.shop/download/6061/server_3')
                ->referer('https://www.coolmoviez.shop/movie/4715/Megafault_(2009)_english_movie.html')
                ->onHeaders(function (ResponseInterface $response) use (&$downloadLink) {
                    $downloadLink = $response->getHeaderLine('location');
                    throw new Exception($downloadLink);
                })
                ->exec();
        } catch (Exception $linkFoundException) {
        }

        return $downloadLink;
    }
}
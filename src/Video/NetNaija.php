<?php

namespace Uticlass\Video;

use Exception;
use Guzwrap\Request;
use Psr\Http\Message\ResponseInterface;
use Queliwrap\Client;
use Throwable;
use Uticlass\Core\Scraper;
use Uticlass\Core\Struct\Traits\InstanceCreator;

/**
 * Extract download link from https://netnaija.com
 */
class NetNaija extends Scraper
{
    protected string $foundLink;

    /**
     * Starts finding link
     * @return $this
     * @throws Throwable
     */
    public function get(): NetNaija
    {
        $link = Client::get($this->url)
            ->execute()
            ->find('a.button:nth-child(4)')
            ->attr('href');

        //Needed to fix uninitialized var access error
        $this->foundLink = '';

        try {
            $count = 1;
            Request::get($link)
                ->onHeaders(function (ResponseInterface $response) use (&$count) {
                    $link = $response->getHeaderLine('location');
                    if (strstr($link, 'sabishare')) {
                        $this->foundLink = $link;
                        throw new Exception($this->foundLink);
                    }

                    if ($count === 2) {
                        $this->foundLink = $response->getHeaderLine('location');
                        throw new Exception($this->foundLink);
                    }
                    $count++;
                })
                ->exec();
        } catch (Throwable $e) {
        }

        return $this;
    }

    /**
     * Get final download link
     * @param string|null $url
     * @return string
     */
    public function linkTwo(?string $url = null): string
    {
        return $this->getDownloadLink($url);
    }

    private function getDownloadLink(?string $url = null): string
    {
        $url ??= $this->foundLink;
        $explodedUrl = explode('/', $url);
        $videoId = end($explodedUrl);
        if (false !== strpos($videoId, 'com-mp4')) {
            $path = parse_url($url)['path'];
            $explodedUrl = explode('/', $path);
            $explodedUrl = explode('-', $explodedUrl[2]);
            $videoId = $explodedUrl[0];
        }

        $constructedApiUrl = "https://api.sabishare.com/token/download/{$videoId}";

        $jsonResponse = Request::get($constructedApiUrl)
            ->exec()
            ->getBody()
            ->getContents();

        $decodedJson = json_decode($jsonResponse);
        return $decodedJson->data->url;
    }
}
<?php

namespace Uticlass\Video;

use Exception;
use Guzwrap\Request;
use Psr\Http\Message\ResponseInterface;
use Queliwrap\Client;
use Throwable;
use Uticlass\Core\Scraper;
use Uticlass\TryDownloadMethod;

/**
 * Extract download link from https://netnaija.com
 */
class NetNaija extends Scraper
{
    protected string $foundLink = '';

    /**
     * Starts finding link
     * @return string
     */
    public function get(): string
    {
        return $this->tryExtractingMethods();
    }

    protected function tryExtractingMethods(): string
    {
        return TryDownloadMethod::create()
            ->addMethod(fn() => $this->method2())
            ->addMethod(fn() => $this->method1())
            ->execute();
    }

    /**
     * @return string
     * @throws Throwable
     */
    public function method1(): string
    {
        try {
            $count = 1;
            Request::get($this->appendDownloadPrefixToUrl())
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

        return $this->getDownloadLink();
    }

    /**
     * @return string
     * @throws Throwable
     */
    public function method2(): string
    {
        return Client::get($this->appendDownloadPrefixToUrl())
            ->sink('download.html')
            ->execute()
            ->find('a#download')
            ->attr('href');
    }

    /**
     * @param string|null $url
     * @return string
     * @throws Throwable
     */
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

    protected function appendDownloadPrefixToUrl() :string
    {
        if ('/' == substr($this->url, -1, 1)){
            return $this->url . 'download';
        }

        return "$this->url/download";
    }

    /**
     * Determine whether url is video url
     * @param string $url
     * @return bool
     */
    public static function isVideoUrl(string $url): bool
    {
        return false !== strpos($url, '/videos/');
    }

    /**
     * Determine whether url is video url
     * @param string $url
     * @return bool
     */
    public static function isForumUrl(string $url): bool
    {
        return false !== strpos($url, '/forum/');
    }
}
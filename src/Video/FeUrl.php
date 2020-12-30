<?php

namespace Uticlass\Video;

use GuzzleHttp\Client;
use Uticlass\Core\Scraper;
use Uticlass\Core\Struct\Traits\InstanceCreator;

class FeUrl extends Scraper
{
    public function get(): string
    {
        if ($this->url) {
            $expUrl = explode('/', $this->url);
            $url = 'https://feurl.com/api/source/' . end($expUrl);

            $client = new Client();
            $request = $client->request('POST', $url);
            $jsonResponse = $request->getBody();
            $objectResponse = json_decode($jsonResponse);

            return $objectResponse->data;
        }

        return '';
    }
}
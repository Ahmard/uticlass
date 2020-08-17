<?php
namespace Uticlass\Video;

use GuzzleHttp\Client;

class FeUrl
{
    protected $url;

    public function get($url) {
        if ($url) {
            $expUrl = explode('/', $url);
            $url = 'https://feurl.com/api/source/'.end($expUrl);

            $client = new Client();
            $request = $client->request('POST', $url);
            $jsonResponse = $request->getBody();
            $objectResponse = json_decode($jsonResponse);

            return $objectResponse->data;
        }
        
        return false;
    }
}
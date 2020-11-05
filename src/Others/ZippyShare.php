<?php

namespace Uticlass\Others;

use Queliwrap\Client;
use Uticlass\Core\Struct\Traits\InstanceCreator;

class ZippyShare
{
    use InstanceCreator;

    protected string $fileUrl;

    public function get()
    {
        $html = Client::get($this->url)->exec()->getHtml();

        //Get video first param
        preg_match("@var a = ([0-9]+)@", $html, $matchFirstParam);
        $a = $matchFirstParam[1];

        //Get video second params
        preg_match("@document\.getElementById\('dlbutton'\)\.omg = \"(.*)\"\.substr\((\d), (\d)\)@", $html, $matchSecondParams);
        $param1 = $matchSecondParams[2];
        $param2 = $matchSecondParams[3];
        $b = strlen(substr($matchSecondParams[1], $param1, $param2));

        //Generate video code
        $videoCode = pow($a, $param2) + $b;

        //Match file information
        preg_match("@document\.getElementById\('dlbutton'\)\.href = \"(.*)\"\+(.*)\+\"(.*)\";@", $html, $matchFileData);
        $parsedUrl = parse_url($this->url);

        $this->fileUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . $matchFileData[1] . $videoCode . $matchFileData[3];

        return $this->fileUrl;
    }

}
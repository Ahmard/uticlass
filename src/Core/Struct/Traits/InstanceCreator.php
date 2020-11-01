<?php


namespace Uticlass\Core\Struct\Traits;


trait InstanceCreator
{
    private string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * Instantiate this class
     * @param string $url
     * @return static
     */
    public static function init(string $url = '')
    {
        return new static($url);
    }
}
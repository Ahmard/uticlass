<?php


namespace Uticlass\Core\Struct\Traits;


trait InstanceCreator
{
    protected string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * Instantiate this class
     * @param string $url
     * @return static
     */
    public static function init(string $url = ''): self
    {
        return new static($url);
    }


    /**
     * Instantiate this class
     * @return static
     */
    public static function create(): self
    {
        return new static('');
    }
}
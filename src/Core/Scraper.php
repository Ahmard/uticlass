<?php


namespace Uticlass\Core;


use Guzwrap\RequestInterface;
use Uticlass\Config;

class Scraper
{
    protected string $url;
    protected RequestInterface $request;

    /**
     * This will help identify requests of the same type and share their cookie
     * @var Config
     */
    private Config $config;

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
    public static function create()
    {
        return new static('');
    }

    /**
     * This will help identify requests of the same type and share their cookies
     * @param Config $config
     * @return $this
     */
    public function useConfig(Config $config): Scraper
    {
        $this->config = $config;
        return $this;
    }

    /**
     * @param RequestInterface $request
     * @return static
     */
    public function useRequest(RequestInterface $request): Scraper
    {
        $this->request = $request;
        return $this;
    }

    protected function getConfig(): Config
    {
        if (!isset($this->config)) {
            $this->config = Config::new();
        }

        return $this->config;
    }
}
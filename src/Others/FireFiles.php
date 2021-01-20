<?php


namespace Uticlass\Others;


use Guzwrap\Core\Post;
use Guzwrap\Core\Redirect;
use Guzwrap\Request;
use GuzzleHttp\Cookie\CookieJar;
use Psr\Http\Message\ResponseInterface;
use QL\Dom\Elements;
use Queliwrap\Client;
use Uticlass\Core\Scraper;

/**
 * Class FireFiles
 * @package Uticlass\Others
 * @link https://firefiles.org/
 */
class FireFiles extends Scraper
{
    private ?string $firstRedirectedUrl = null;

    private string $domain = 'https://firefiles.org';

    public function __construct(string $url)
    {
        parent::__construct($url);

        $this->useRequest(
            Request::withCookie(new CookieJar())
                ->redirects(function (Redirect $redirect) {
                    $redirect->max(10);
                })
        );
    }

    public function get(): string
    {
        $inputs = [];
        Client::get($this->url)
            ->useRequest($this->request)
            ->onHeaders(function (ResponseInterface $response) {
                if (!$this->firstRedirectedUrl) {
                    $this->firstRedirectedUrl = $this->domain . $response->getHeaderLine('Location');
                }
            })
            ->execute()
            ->find('#container > form input')
            ->each(function (Elements $input) use (&$inputs) {
                $inputs[$input->attr('name')] = $input->attr('value');
            });

        return $this->secondPage($inputs);
    }

    private function secondPage(array $inputs): string
    {
        $secondInputs = [];
        Client::post(function (Post $post) use ($inputs) {
            $post->url($this->firstRedirectedUrl);
            foreach ($inputs as $name => $value) {
                $post->field($name, $value);
            }
        })
            ->useRequest($this->request)
            ->execute()
            ->find('form input')
            ->each(function (Elements $input) use (&$secondInputs) {
                $secondInputs[$input->attr('name')] = $input->attr('value');
            });

        return $this->thirdPage($secondInputs);
    }

    private function thirdPage(array $inputs): string
    {
        $buttonOnClickAttr = Client::post(function (Post $post) use ($inputs) {
            $post->url($this->firstRedirectedUrl);
            foreach ($inputs as $name => $value) {
                $post->field($name, $value);
            }
        })
            ->useRequest($this->request)
            ->execute()
            ->find('button[id="downloadbtn"]')
            ->attr('onclick');

        preg_match("@window\.open\('(.*)','_self'\)@", $buttonOnClickAttr, $matches);

        return $matches[1];
    }
}
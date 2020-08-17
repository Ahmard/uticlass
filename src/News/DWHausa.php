<?php
namespace Uticlass\News;

use Uticlass\Struct\Abstracts\NewsAbstract;
use Queliwrap\Client;

class DWHausa extends NewsAbstract
{
    protected $websiteUrl;
    
    protected $newsList = array();

    protected $error = null;


    public function __construct()
    {
        $this->websiteUrl = 'https://m.dw.com/ha/';
    }
    
    public function fetch() : object
    {
        $client = Client::get($this->websiteUrl);

        $client->then(function($ql) {
            //News
            $ql->find('.news')->each(function($div) {
                //Link and Text
                $a = $div->find('a');
                $href = $this->makeUrl($a->attr('href'));
                $text = trim($a->find('h2')->eq(0)->text());
                
                //Time
                $time = trim($div->find('span.date')->eq(0)->text());
                if ($text) {
                    $this->newsList[] = [
                        'text' => $text,
                        'href' => $href,
                        'time' => $time,
                    ];
                }
            });
            
            //Others
            $ql->find('.col2.avTeaser')->each(function($div){
                $href = null;
                $summary = null;
                $related = [];
                $name = $div->find('input[name="display_name"]')->eq(0)->val();
                $date = $div->find('input[name="display_date"]')->eq(0)->val();
               
                //dd($date);
                $div->find('.teaserContentWrap.information.dynamic')->each(function($div) use(&$href, &$summary){
                    $href = trim($div->find('a:eq(0)')->attr('href'));
                    $summary = trim($div->find('p:eq(0)')->text());
                });
                
                $div->find('.teaserContentWrap.information.dynamic')->each(function($div) use(&$related){
                    $related[] = [
                        'name' => $div->find('h2:eq(0)')->text(),
                        'href' => $div->find('a:eq(0)')->attr('href'),
                        'summary' => $div->find('p:eq(0)')->text()
                    ];
                });
                
                $this->newsList[] = [
                    'name' => $name,
                    'href' => $href,
                    'date' => $date,
                    'summary' => $summary,
                    'related' => $related
                ];
            });
        });
        
        $client->otherwise(function($err) {
            $this->error = $err;
        });

        return $this;
    }

}
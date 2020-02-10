<?php


namespace App\Service;

use App\Models\Constellation;
use Carbon\Carbon;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class CrawlService
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function mainCrawl()
    {
        $date = Carbon::today()->format('Y-m-d');

        //取得十二星座資料連結
        $constellation_url_array = $this->getConstellationUrl('http://astro.click108.com.tw/');

        if ($constellation_url_array) {
            foreach ($constellation_url_array as $value) {
                $detail_data = $this->getConstellationData($value['url']);
                $data = [
                    'date' => $date,
                    'constellation_name' => $value['name'],
                    'overall_level' => $detail_data['level_array'][0],
                    'overall_content' => $detail_data['content_array'][0],
                    'love_level' => $detail_data['level_array'][1],
                    'love_content' => $detail_data['content_array'][1],
                    'business_level' => $detail_data['level_array'][2],
                    'business_content' => $detail_data['content_array'][2],
                    'fortune_level' => $detail_data['level_array'][3],
                    'fortune_content' => $detail_data['content_array'][3],
                ];

                Constellation::query()->create($data);
            }
        }
    }

    //取得十二星座資料連結
    public function getConstellationUrl($url)
    {
        $crawl_response = $this->client->request('GET', $url);

        $data = $crawl_response->filter('.STAR12_BOX')->filter('li')->each(function (Crawler $node) {
            return [
                'name' => $node->text(),
                'url'   => explode('RedirectTo=', urldecode($node->filter('a')->attr('href')))[1]
            ];
        });

        return $data;
    }

    public function getConstellationData($url)
    {
        $crawl_response = $this->client->request('GET', $url);

        $level_array = $crawl_response->filter('.FORTUNE_INDEX_AERA')->filter('.STAR_LIGHT')->each(function (Crawler $node) {

            //取得圖片檔
            $img = urldecode($node->filter('img')->attr('src'));

            //移除附檔明
            $img_string = explode('.png', $img, -1)[0];

            //取得圖檔的星數
            return $level = substr($img_string, -1, 1);

        });

        $content = $crawl_response->filter('.TODAY_CONTENT')->filter('p')->each(function (Crawler $node) {
            return $node->filter('p')->text();
        });

        $content_array = array();
        for ($i = 0; $i <= count($content); $i++) {
            if ($i % 2 == 1) {
                array_push($content_array, $content[$i]);
            }
        }

        return [
            'level_array' => $level_array,
            'content_array' => $content_array
        ];
    }
}
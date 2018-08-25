<?php
function debug($ar) {
    echo '<pre>';
    print_r($ar);
    echo '</pre>';
}

class Test {
    private $matches = [];
    public $views;
    public $urls;
    public $traffic;
    public $crawlers;
    public $statusCodes;

    public function __construct($pattern, $filename)
    {
        //проверяем верно ли указан шаблон. Заполняем массив $matches данными соответсвующими регулярному выражению
        if(file_exists($filename)) {
            $text = file_get_contents($filename);
            preg_match_all($pattern, $text, $this->matches);
        }else {
            exit ("Файл не найден");
        }
    }

    //объединяем одинаковые Urls и подсчитываем уникальные
    public function getUniqUrls($url)
    {
        $this->urls = count(array_count_values($this->matches["$url"]));
        return $this;
    }

    //Суммируем объем трафика
    public function getSumTraffic($traffic)
    {
        $this->traffic = array_sum($this->matches["$traffic"]);
        return $this;
    }

    //Считаем количество запросов от поисковиков
    public function getCrawlers($bots)
    {
        $this->crawlers = array_count_values(array_diff($this->matches["$bots"], array('')));
        return $this;
    }

    //Подсчитываем коды ответов
    public function getStatusCodes($codes)
    {
        $this->statusCodes = array_count_values($this->matches["$codes"]);
        return $this;
    }

    //Подсчитываем колличесво просмотров
    public function getViews($views)
    {
        $this->views = count($this->matches["$views"]);
        return $this;
    }
}

$pattern = '/^([\d\.]+)[\s|-]+\[[\S ]+\][ "\w]+([\/a-z]+[\S\s]+?)"\s(\d+)[-\s]+(\d+)[\S\s]+?"[\s\S]+?\)[\s]?(Google|Bing|Yandex|Baidu)*.*$/m';

$obj = new Test($pattern,'access.log');
$obj->getViews(1)->getUniqUrls(2)->getSumTraffic(4)->getCrawlers(5)->getStatusCodes(3);



debug(json_encode($obj, JSON_PRETTY_PRINT));

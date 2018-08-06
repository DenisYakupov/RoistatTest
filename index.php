<?php
function debug($ar) {
    echo '<pre>';
    print_r($ar);
    echo '</pre>';
}

class Test {
    public $matches = [];
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
        } else ("Файл не найден");
    }

    //объединяем одинаковые Urls и подсчитываем уникальные
    public function getUniqUrls($url)
    {
        $this->urls = count(array_count_values($this->matches["$url"]));
    }

    //Суммируем объем трафика
    public function getSumTraffic($traffic)
    {
        $this->traffic = array_sum($this->matches["$traffic"]);
    }

    //Считаем количество запросов от поисковиков
    public function getCrawlers($bots)
    {
        $this->crawlers = array_count_values(array_diff($this->matches["$bots"], array('')));
    }

    //Подсчитываем коды ответов
    public function getStatusCodes($codes)
    {
        $this->statusCodes = array_count_values($this->matches["$codes"]);
    }

    //Подсчитываем колличесво просмотров
    public function getViews($views)
    {
        $this->views = count($this->matches["$views"]);
    }
}

$pattern = '/^([\d\.]+)[\s|-]+\[[\S ]+\][ "\w]+([\/a-z]+[\S\s]+?)"\s(\d+)[-\s]+(\d+)[\S\s]+?"[\s\S]+?\)[\s]?(Google|Bing|Yandex|Baidu)*.*$/m';

$obj = new Test($pattern,'access.log');
$obj->getViews(1);
$obj->getUniqUrls(2);
$obj->getSumTraffic(4);
$obj->getCrawlers(5);
$obj->getStatusCodes(3);


debug(json_encode($obj, JSON_PRETTY_PRINT));

<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/12
 * Time: 15:19
 */

namespace Mirror\ApiBundle\Util;


class WeatherHelper{
    private static $apiUrl = 'http://v.juhe.cn/weather/index';
    private static $key = '3f01a5ac01074a9a6faeffa8c944da88';

    /**
     * @param $cardNo
     * @return string
     */
    public static function getResult($cityname){
        $arguments=array('key'=>self::$key,'cityname'=>$cityname);
        $arguments=http_build_query($arguments);
        $result=CurlHelper::httpGet(self::$apiUrl.'?'.$arguments);
        return json_decode($result->getResult(),true);
    }

}
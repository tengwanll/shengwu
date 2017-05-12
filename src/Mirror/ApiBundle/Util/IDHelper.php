<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/12
 * Time: 15:19
 */

namespace Mirror\ApiBundle\Util;


class IDHelper{
    private static $apiUrl = 'http://apis.juhe.cn/idcard/index';
    private static $key = '0ba8d2d3e7ba369cf7e06bcfc21ac170';

    /**
     * @param $cardNo
     * @return string
     */
    public static function getResult($cardNo){
        $arguments=array('key'=>self::$key,'cardno'=>$cardNo);
        $arguments=http_build_query($arguments);
        $result=CurlHelper::httpGet(self::$apiUrl.'?'.$arguments);
        return json_decode($result->getResult(),true);
    }

}
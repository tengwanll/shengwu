<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/12
 * Time: 11:49
 */

namespace Mirror\ApiBundle\Util;


class TelephoneHelper{
    private static $apiUrl = 'http://apis.juhe.cn/mobile/get';
    private static $key = 'c2281f0ba76064e4da369ff8a51e50a4';

    public static function getResult($phone){
        $arguments=array('key'=>self::$key,'phone'=>$phone);
        $arguments=http_build_query($arguments);
        $result=CurlHelper::httpGet(self::$apiUrl.'?'.$arguments);
        $result=json_decode($result->getResult(),true);
        return $result;
    }

}
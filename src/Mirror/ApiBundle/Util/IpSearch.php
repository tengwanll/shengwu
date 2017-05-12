<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2016/9/30
 * Time: 10:24
 */

namespace Mirror\ApiBundle\Util;


class IpSearch
{
    private static $url='http://apis.juhe.cn/ip/ip2addr';
    private static $key='730643e2fb5af9b871e0f8b5b3b5d686';

    public static function getPlace($ip){
        $arguments=array('key'=>self::$key,'ip'=>$ip);
        $arguments=http_build_query($arguments);
        $result=CurlHelper::httpGet(self::$url.'?'.$arguments);
        return json_decode($result->getResult(),true);
    }
}
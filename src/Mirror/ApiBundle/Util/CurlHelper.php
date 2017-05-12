<?php

namespace Mirror\ApiBundle\Util;

use Mirror\ApiBundle\Util\CurlReturn;

class CurlHelper {


    public static function httpGet($server) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER ['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $server);
        curl_setopt(
            $curl,
            CURLOPT_HTTPHEADER,
            array(
                "Accept: application/json",
            )
        );
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $errInfo = curl_error($curl);;

        curl_close($curl);
        return new CurlReturn($result,$status,$errInfo);
    }

    /**
     * 正规的http post请求,多用于表单提交
     * @param $server
     * @param $param
     * @return \Mirror\ApiBundle\Util\CurlReturn
     */
    public static function httpPost($server, $param) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER ['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $server);
        $o = "";
        if (is_array($param)) {
            foreach ($param as $k => $v) {
                $o .= "$k=".urlencode($v)."&";
            }
        } else {
            $o = $param;
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, $o);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt(
            $curl,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/x-www-form-urlencoded',
            )
        );
        $result = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $errInfo = curl_error($curl);;

        curl_close($curl);

        return new CurlReturn($result,$status,$errInfo);
    }

    /**
     * curl封装
     * @param $url
     * @param string $method
     * @param string $data
     * @param null $header
     * @param int $timeout
     * @return \Mirror\ApiBundle\Util\CurlReturn
     */
    public static function curlRequest($url,$method='post', $data='', $header=null, $timeout=30){

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method); //设置请求方式

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_HEADER, 0);
        if(strtoupper($method)=='POST'){
            curl_setopt($ch, CURLOPT_POST, true);
        }
        if(is_array($data)){
            $o=http_build_query($data);
        }else{
            $o=$data;
        }
        $headArray=array();
        $headArray[]="Content-Type: application/json";
        if($header){
            $headArray[]=$header;
        }
        curl_setopt($ch,CURLOPT_HTTPHEADER,$headArray);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $o);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

        $response = curl_exec($ch);

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $errInfo = curl_error($ch);

        curl_close($ch);

        return new CurlReturn($response,$status,$errInfo);
    }
}
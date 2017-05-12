<?php

namespace Mirror\ApiBundle\Util;

class Helper {

    public static function getc($arr, $param, $default = '', $unexpected = '') {
        if (!isset ($arr) || !$arr) {
            return $default;
        } else {
            if (!isset ($arr [$param])) {
                return $default;
            } else {
                if ($arr [$param] === $unexpected) {
                    return $default;
                }
            }
        }

        return $arr [$param];
    }

    public static function gets($param, $default = '', $unexpected = '') {
        if (!isset ($param) || !$param) {
            return $default;
        } else {
            if ($param === $unexpected) {
                return $default;
            }
        }

        return $param;
    }

    public static function guessClientOS() {
        $agent = $_SERVER ['HTTP_USER_AGENT'];

        if (preg_match('/ipad/i', $agent)) {
            return 'IPAD';
        } elseif (preg_match('/iphone\s*os/i', $agent)) {
            return 'IPHONE';
        } elseif (preg_match('/android\s*os/i', $agent)) {
            return 'ANDROID';
        } elseif (preg_match('/wp7/i', $agent)) {
            return 'WP7';
        } elseif (preg_match('/wp8/i', $agent)) {
            return 'WP8';
        }

        return 'GENERAL';
    }

    public static function formatBytes($bytes, $precision = 2) {
        $units = array(
            '',
            'K',
            'M',
            'G',
            'T',
        );

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision).$units [$pow];
    }

    public static function toBytes($fmt) {
        $u = array(
            '',
            'K',
            'M',
            'G',
            'T',
        );
        $p = strtoupper(substr($fmt, -1, 1));
        $k = array_search($p, $u);

        return round($fmt * pow(1024, $k));
    }

    public static function toStr($collection) {
        $str = '';
        foreach ($collection as $temp) {
            if ($temp != '') {
                $str = $str.$temp.',';
            }
        }
        if ($str == '') {
            return $str;
        }
        $length = strlen($str);

        return substr($str, 0, $length - 1);
    }

    public static function makeQueryString($dql, $where = array()) {
        if (count($where) == 0) {
            return $dql;
        }
        $whereString = $where [0];
        for ($i = 1; $i < count($where); $i++) {
            $whereString = $whereString.' and '.$where [$i];
        }

        return $dql.' where '.$whereString;
    }

    public static function fillLength($str, $length, $char = '0') {
        while (strlen($str) < $length) {
            $str = $char.$str;
        }

        return $str;
    }

    public static function arraySortByKey($arr, $keys, $type = 'asc') {
        $keysvalue = $new_array = array();
        foreach ($arr as $k => $v) {
            $keysvalue [$k] = $v [$keys];
        }
        if ($type == 'asc') {
            asort($keysvalue);
        } else {
            arsort($keysvalue);
        }
        reset($keysvalue);
        foreach ($keysvalue as $k => $v) {
            $new_array [] = $arr [$k];
        }

        return $new_array;
    }

    public static function generatePassword($password) {
        return md5($password);
    }

    public static function uuid($prefix = '') {
        if (function_exists('com_create_guid')){
            return com_create_guid();
        }else{
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid =
                $prefix.substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12);
            return $uuid;
        }
    }

    /**
     * 数组转成xml
     * @param array $value
     * @return string
     */
    public static function toxml(array $value){
        $xml = "<xml>";
        foreach ($value as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<".$key.">".$val."</".$key.">";
            } else {
                $xml .= "<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    /**
     * @param $date
     * @return \DateTime
     */
    public static function createDate($date){
        if(date_create_from_format('Y/m/d H:i:s',$date)){
            $time=date_create_from_format('Y/m/d H:i:s',$date);
        }else{
            $time=date_create_from_format('Y-m-d H:i:s',$date);
        }
        return $time;
    }

    /**
     * 随机指定长度的明文数字字符密码
     * @param $number
     * @return string
     */
    public static function getRandPassword($number){
        $arr=array(1,2,3,4,5,6,7,8,9,0,'a','b','c','d','e','f','g','h','i','g','k','l','m','n','o','p','q','r','s','t','u','i','w','h','y','z');
        $password='';
        for($i=0;$i<$number;$i++){
            $result=array_rand($arr,1);
            $password.=$arr[$result];
        }
        return $password;
    }

    /**
     * @param $file
     * @return string
     */
    public static function base64Photo($file){
        $image_info = getimagesize($file);
        return "data:{$image_info['mime']};base64," . chunk_split(base64_encode(file_get_contents($file)));
    }

    /**
     * @param $data
     * @param $newFile
     * @return bool
     */
    public static function getBase64Photo($data,$newFile)
    {
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $data, $result)) {
            $type = $result[2];
            $newFile = $newFile.".".$type;
            if (file_put_contents($newFile, base64_decode(str_replace($result[1], '', $data)))) {
                return true;
            }
        }
        return false;
    }
}

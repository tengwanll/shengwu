<?php
/**
 * Created by PhpStorm.
 * User: rpzhan
 * Date: 15/12/5
 * Time: 18:42
 */

namespace Mirror\ApiBundle\Util;

/**
 * 验证
 * Class ValidateHelper
 * @package Mirror\ApiBundle\Util
 */
class ValidateHelper {

    /**
     * 验证是否为指定长度的字母/数字组合
     * @param $num1
     * @param $num2
     * @param $str
     * @return bool
     */
    public static function isLetterDigit($num1, $num2, $str) {
        Return (preg_match("/^[a-zA-Z0-9]{".$num1.",".$num2."}$/", $str)) ? true : false;
    }

    /**
     * 验证是否为指定长度数字
     * @param $num1
     * @param $num2
     * @param $str
     * @return bool
     */
    public static function isDigit($num1, $num2, $str) {
        return (preg_match("/^[0-9]{".$num1.",".$num2."}$/i", $str)) ? true : false;
    }

    /**
     * 验证是否为指定长度汉字
     * @param $num1
     * @param $num2
     * @param $str
     * @return bool
     */
    public static function isCharacters($num1, $num2, $str) {
        return (preg_match("/^([\x81-\xfe][\x40-\xfe]){".$num1.",".$num2."}$/", $str)) ? true : false;
    }

    /**
     * 验证身份证号码
     * @param $str
     * @return bool
     */
    public static function isIDCard($str) {
        return (preg_match('/(^([\d]{15}|[\d]{18}|[\d]{17}x)$)/', $str)) ? true : false;
    }

    /**
     * 验证邮件地址
     * @param $str
     * @return bool
     */
    public static function isEmail($str) {
        return (preg_match('/^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,4}$/', $str)) ? true : false;
    }

    /**
     * 验证手机号码
     * @param $str
     * @return bool
     */
    public static function isMobile($str) {
        return preg_match("/^((13[0-9])|(15[^4,\D])|(18[0-9])|(17[0-9]))\d{8}$/", $str);
    }

    /**
     * 验证电话号码
     * @param $str
     * @return bool
     */
    public static function isPhone($str) {
        return (preg_match("/^((\(\d{3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}$/", $str)) ? true : false;
    }

    /**
     * 验证邮编
     * @param $str
     * @return bool
     */
    public static function isZip($str) {
        return (preg_match("/^[1-9]\d{5}$/", $str)) ? true : false;
    }

    /**
     * 验证url地址
     * @param $str
     * @return bool
     */
    public static function isUrl($str) {
        return (preg_match(
            "/^http[s]?:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/",
            $str
        )) ? true : false;
    }
}
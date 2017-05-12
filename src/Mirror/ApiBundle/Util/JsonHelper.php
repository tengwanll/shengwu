<?php
/**
 * Created by PhpStorm.
 * User: rpzhan
 * Date: 15/12/5
 * Time: 17:10
 */

namespace Mirror\ApiBundle\Util;

class JsonHelper {

    public static function get($json, $param, $default = '', $notExpected = '') {
        if (!$json) {
            return $default;
        }
        if (!isset ($json[$param])) {
            return $default;
        }

        if ($json [$param] === $notExpected) {
            return $default;
        }

        if (is_array($json[$param])) {
            return $json[$param];
        }

        return trim($json[$param]);
    }

}
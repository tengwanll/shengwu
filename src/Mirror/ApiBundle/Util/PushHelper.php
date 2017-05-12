<?php

namespace Mirror\ApiBundle\Util;

use Mirror\ApiBundle\Common\Constant;
use Mirror\ApiBundle\Util\Push\PushSDK;

class PushHelper {

    public static function pushAndroid($message) {
        $sdk = new PushSDK (Constant::$push_android_api_key, Constant::$push_android_secret_key);
        $sdk->setDeviceType(3);
        $opts = array(
            'msg_type' => 0,
        );
        //$rs = $sdk->pushMsgToSingleDevice ( '3568717267711225872', $message, $opts );
        $rs = $sdk->pushMsgToAll($message, $opts);
    }

    public static function pushSingleAndroid($message, $channelId) {
        $sdk = new PushSDK (Constant::$push_android_api_key, Constant::$push_android_secret_key);
        $sdk->setDeviceType(3);
        $opts = array(
            'msg_type' => 0,
        );
        $rs = $sdk->pushMsgToSingleDevice($channelId, $message, $opts);
        $error=$sdk->getLastErrorCode();
        // $rs = $sdk->pushMsgToAll ( $message, $opts );
    }

    public static function pushIos($message) {
        $sdk = new PushSDK (Constant::$push_ios_api_key, Constant::$push_ios_secret_key);
        $sdk->setDeviceType(4);
        $aps = array(
            'alert' => $message['content'],
            'sound' => 'default',
            'badge' => 0,
        );
        unset($message['content']);
        $pushMessage = array(
            'aps' => $aps,
        );
        $pushMessage = array_merge($pushMessage, $message);
        $pushMessage = json_encode($pushMessage);
        //deploy_status 1 开发模式  2生产模式
        $opts = array(
            'msg_type' => 1,
            'deploy_status' => 1,
        );
        try {
            //$rs = $sdk->pushMsgToSingleDevice ( $channelId, $pushMessage, $opts );
            $rs = $sdk->pushMsgToAll($pushMessage, $opts);
        } catch (\Exception $e) {
            echo 111;
        }
    }

    public static function pushSingleIos($message, $channelId) {
        $sdk = new PushSDK (Constant::$push_ios_api_key, Constant::$push_ios_secret_key);
        $sdk->setDeviceType(4);
        $aps = array(
            'alert' => $message['content'],
            'sound' => 'default',
            'badge' => 0,
        );
        unset($message['content']);
        $pushMessage = array(
            'aps' => $aps,
        );
        $pushMessage = array_merge($pushMessage, $message);
        $pushMessage = json_encode($pushMessage);
        //deploy_status 1 开发模式  2生产模式
        $opts = array(
            'msg_type' => 1,
            'deploy_status' => 1,
        );
        try {
            $rs = $sdk->pushMsgToSingleDevice($channelId, $pushMessage, $opts);
            // $rs = $sdk->pushMsgToAll ($pushMessage, $opts );
        } catch (\Exception $e) {
            echo 111;
        }
    }
}

?>
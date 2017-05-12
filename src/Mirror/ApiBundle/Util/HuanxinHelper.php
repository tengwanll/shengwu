<?php

namespace Mirror\ApiBundle\Util;

use Mirror\ApiBundle\Common\Constant;

class HuanxinHelper {
    /**
     * 刷新环信token
     * @return string
     */
    public static function refreshToken() {
        $url = Constant::$HUANXIN_TOKEN_URL;
        $url = sprintf($url, Constant::$ORG_NAME, Constant::$APP_NAME);
        $arguments=array(
            'grant_type'=>'client_credentials',
            'client_id'=>Constant::$CLIENT_ID,
            'client_secret'=>Constant::$CLIENT_SECRET
        );
        $arguments=json_encode($arguments);
        $rr = CurlHelper::curlRequest($url,'POST',$arguments);
        $result = $rr->getResult();
        $json = json_decode($result, true);
        $token = Helper::getc($json, 'access_token', '');
        $expires = Helper::getc($json, 'expires_in', 0);
        $application=Helper::getc($json,'application','');
        $now = time() + $expires;
        $args = array(
            'access_token' => $token,
            'expires' => $now,
            'application'=>$application
        );
        $context = json_encode($args);
        $path = HuanxinHelper::getConfigPath();

        file_put_contents($path, $context);

        return $token;
    }

    /**
     * 获取环信token
     * @return string
     */
    public static function getToken() {
        $path = HuanxinHelper::getConfigPath();
        $str = file_get_contents($path);
        $json = json_decode($str, true);
        $token = Helper::getc($json, 'access_token', '');
        $expires = Helper::getc($json, 'expires', 0);
        $now = time();
        if ($token == '' || $now >= $expires) {
            return HuanxinHelper::refreshToken();
        }

        return $token;
    }

    /**
     * token路径
     * @return string
     */
    public static function getConfigPath() {
        $dir = __DIR__;
        $os = php_uname('s');
        $os = strtoupper($os);
        $split = "/";
        if (strpos($os, 'WINDOWS')) {
            $split = "\\";
        }
        return $dir.$split.'HXToken.json';
    }

    /**
     * 环信创建用户
     * @param $username
     * @param $password
     * @param string $nickname
     * @return CurlReturn
     */
    public static function createUser($username,$password,$nickname=''){
        $path=Constant::$HUANXIN_CREATE_USER;
        $url = sprintf($path, Constant::$ORG_NAME, Constant::$APP_NAME);
        $token=HuanxinHelper::getToken();
        $header="Authorization:Bearer ".$token;
        $method='POST';
        $arguments=array(
            'username'=>$username,
            'password'=>$password
        );
        if($nickname){
            $arguments['nickname']=$nickname;
        }
        $arguments=json_encode($arguments);
        $hxUser=CurlHelper::curlRequest($url,$method,$arguments,$header);
        return array('result'=>$hxUser->getResult(),'url'=>$url,'method'=>$method,'arguments'=>$arguments,'status'=>$hxUser->getStatus(),'errInfo'=>$hxUser->getErrInfo());
    }

    /**
     * 添加环信用户好友
     * @param $username
     * @param $friendName
     * @return array
     */
    public static function createFriend($username,$friendName){
        $path=Constant::$HUANXIN_ADD_FRIEND;
        $url = sprintf($path, Constant::$ORG_NAME, Constant::$APP_NAME,$username,$friendName);
        $token=HuanxinHelper::getToken();
        $header="Authorization:Bearer ".$token;
        $method='POST';
        $hxUser=CurlHelper::curlRequest($url,$method,'',$header);
        return array('result'=>$hxUser->getResult(),'url'=>$url,'method'=>$method,'arguments'=>'','status'=>$hxUser->getStatus(),'errInfo'=>$hxUser->getErrInfo());
    }

    /**
     * 环信删除好友
     * @param $username
     * @param $friendName
     * @return array
     */
    public static function deleteFriend($username,$friendName){
        $path=Constant::$HUANXIN_ADD_FRIEND;
        $url = sprintf($path, Constant::$ORG_NAME, Constant::$APP_NAME,$username,$friendName);
        $token=HuanxinHelper::getToken();
        $header="Authorization:Bearer ".$token;
        $method='DELETE';
        $hxUser=CurlHelper::curlRequest($url,$method,'',$header);
        return array('result'=>$hxUser->getResult(),'url'=>$url,'method'=>$method,'arguments'=>'','status'=>$hxUser->getStatus(),'errInfo'=>$hxUser->getErrInfo());
    }
}
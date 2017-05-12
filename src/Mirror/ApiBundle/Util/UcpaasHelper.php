<?php
/**
 * Created by PhpStorm.
 * User: rpzhan
 * Date: 16/1/19
 * Time: 16:16
 */

namespace Mirror\ApiBundle\Util;

use Mirror\ApiBundle\Common\Constant;

/**
 * 发送短信帮助类
 * Class UcpaasHelper
 * @package Mirror\ApiBundle\Util
 */
class UcpaasHelper {

    /**
     * 根据模版发送短信
     * @param $to
     * @param $templateId
     * @param null $param
     * @param string $type
     * @throws Exception
     */
    public static function sendTemplateSMS($to, $templateId, $param = null, $type = 'json') {
        $options['accountsid'] = Constant::$UCPASS_ACCOUNT_SID;
        $options['token'] = Constant::$UCPASS_AUTH_TOKEN;
        $ucpass = new Ucpaas($options);
        if (!empty($to)) {
            if (is_array($to)) {
                foreach ($to as $telephone) {
                    $ucpass->templateSMS(Constant::$UCPAAS_APP_ID, $telephone, $templateId, $param);
                }
            } else {
                $ucpass->templateSMS(Constant::$UCPAAS_APP_ID, $to, $templateId, $param);
            }
        }
    }

}
<?php

namespace Mirror\ApiBundle\Service;

use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use Mirror\ApiBundle\Common\Code;
use Mirror\ApiBundle\Common\Constant;
use Mirror\ApiBundle\Model\TelephoneCodeModel;
use Mirror\ApiBundle\Util\Ucpaas;
use Mirror\ApiBundle\ViewModel\ReturnResult;

/**
 * 短信验证服务
 * @Service("telephone_code_service")
 * Class TelephoneCodeService
 * @package Mirror\ApiBundle\Service
 */
class TelephoneCodeService {

    private $telephoneCodeModel;

    /**
     * @InjectParams({
     *     "telephoneCodeModel" = @Inject("telephone_model"),
     * })
     * TelephoneCodeService constructor.
     * @param TelephoneCodeModel $telephoneCodeModel
     */
    public function __construct(TelephoneCodeModel $telephoneCodeModel) {
        $this->telephoneCodeModel = $telephoneCodeModel;
    }

    /**
     * 生成验证码
     * @param $telephone
     * @return string
     */
    public function createValidCode($telephone) {
        $done = false;
        $code = sprintf("%s", mt_rand(1000, 9999));

        // TODO 同一个手机号码数据异常
        do {
            $activity = $this->telephoneCodeModel->getOneByCriteria(
                array(
                    'telephone' => $telephone,
                    'code' => $code,
                    'status'=>1,
                )
            );
            if ($activity) {
                $code = sprintf("%s", rand(100000, 999999));
            } else {
                $done = true;
            }
        } while (!$done);

        return $code;
    }

    /**
     * 发送验证码
     * @param $telephone
     * @return ReturnResult
     */
    public function sendCode($telephone) {
        $rr = new ReturnResult();
        $arguments = array(
            'telephone' => $telephone,
            '>' => array('validBeginTime' => time() - 60),
        );
        $result = $this->telephoneCodeModel->getCountBy($arguments);
        if ($result) {
            $rr->errno = Code::$code_exist;

            return $rr;
        }
        $arguments = array(
            'telephone' => $telephone,
            'validity' => 0,
        );
        //让之前的验证码失效
        $result=$this->telephoneCodeModel->getByParams($arguments);
        foreach($result as $item){
            /**@var $item \Mirror\ApiBundle\Entity\TelephoneCode*/
            $item->setValidity(1);
            $this->telephoneCodeModel->flush($item);
        }
        $code = $this->createValidCode($telephone);

        // 发送验证码
        $this->ucpassSendSms($telephone, $code);

        // 保存记录
        $telephoneCode = new TelephoneCode();
        $telephoneCode->setTelephone($telephone);
        $telephoneCode->setCode($code);
        $expire = time();
        $telephoneCode->setValidBeginTime($expire);
        // TODO should query by system setting
        // 设置30分钟过期
        $expires = 30;
        $expire += $expires * 60;
        $telephoneCode->setValidity(0);
        $telephoneCode->setValidEndTime($expire);
        $telephoneCode->setStatus(1);
        $telephoneCode->setCreateTime(new \DateTime());
        $telephoneCode->setUpdateTime(new \DateTime());
        $this->telephoneCodeModel->save($telephoneCode);
//        $rr->result=array('code'=>$code);
        return $rr;
    }

    /**
     * 云之讯发送验证码
     * @param $telephone
     * @param $code
     */
    public function ucpassSendSms($telephone, $code) {
        $options['accountsid'] = Constant::$UCPASS_ACCOUNT_SID;
        $options['token'] = Constant::$UCPASS_AUTH_TOKEN;
        $ucpass = new Ucpaas($options);
        $appId = Constant::$UCPAAS_APP_ID;
        $to = $telephone;
        $templateId = Constant::$UCPAAS_TEMPLATE_ID;
        $param = $code.',1';
        $result = $ucpass->templateSMS($appId, $to, $templateId, $param);
    }


    /**
     * 云之讯重置密码
     * @param $telephone
     * @param $code
     */
    public function ucpassResetPwdSendSms($telephone, $code) {
        $options['accountsid'] = Constant::$UCPASS_ACCOUNT_SID;
        $options['token'] = Constant::$UCPASS_AUTH_TOKEN;
        $ucpass = new Ucpaas($options);
        $appId = Constant::$UCPAAS_APP_ID;
        $to = $telephone;
        $templateId = Constant::$UCPAAS_RESETPWD_TEMPLATE_ID;
        $param = $telephone.','.$code;
        $result = $ucpass->templateSMS($appId, $to, $templateId, $param);
    }


    /**
     * 验证短信验证码
     * @param $telephone
     * @param $code
     * @return bool
     */
    public function validateCode($telephone, $code) {
        $telephoneCode = $this->telephoneCodeModel->getCountBy(
            array(
                'telephone' => $telephone,
                'code' => $code,
                'status' =>1,
                '>'=>array('validEndTime'=>time()),
                'validity'=>0
            )
        );
        if (!$telephoneCode) {
            return false;
        }
        return true;
    }

    /**
     * 短信验证成功
     * @param $telephone
     * @param $code
     * @return ReturnResult
     */
    public function completeValid($telephone, $code) {
        $rr=new ReturnResult();
        $telephoneCodes = $this->telephoneCodeModel->getByParams(
            array(
                'telephone' => $telephone,
                'code' => $code,
                'status' =>1,
                '>'=>array('validEndTime'=>time())
            )
        );
        foreach($telephoneCodes->getIterator() as $telephoneCode){
            /** @var $telephoneCode \Mirror\ApiBundle\Entity\TelephoneCode */
            $telephoneCode->setValidity(1);
            $telephoneCode->setValidTime(new \DateTime());
            $this->telephoneCodeModel->save($telephoneCode);
        }
        return $rr;
    }
    /**
     * 获取验证码列表
     * @param $pageable
     * @param $telephone
     * @return ReturnResult
     */
    public function getList($pageable, $telephone) {
        $rr = new ReturnResult();
        $arguments = array();
        if ($telephone) {
            $arguments = array(
                'like' => array('telephone' => '%'.$telephone.'%'),
            );
        }
        $result = $this->telephoneCodeModel->getByParams($arguments, $pageable,'createTime desc');
        $arr = array();
        foreach ($result->getIterator() as $code) {
            /** @var $code \Mirror\ApiBundle\Entity\TelephoneCode */
            $arr[] = array(
                'id' => $code->getId(),
                'telephone' => $code->getTelephone(),
                'code' => $code->getCode(),
                'validTime' => $code->getValidTime() ? $code->getValidTime()->getTimestamp() : 0,
            );
        }
        $rr->result = array(
            'rows' => $arr,
            'total' => $result->count(),
        );

        return $rr;
    }
}

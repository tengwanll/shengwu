<?php
/**
 * Created by PhpStorm.
 * User: rpzhan
 * Date: 15/11/27
 * Time: 22:53
 */

namespace Mirror\ApiBundle\Controller;

use Mirror\ApiBundle\Annotation\OAuth;
use Mirror\ApiBundle\Common\Code;
use Mirror\ApiBundle\Common\Constant;
use Mirror\ApiBundle\Util\ValidateHelper;
use Mirror\ApiBundle\ViewModel\ReturnResult;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * 短信服务
 * @Route("/sms")
 * Class SmsController
 * @package Mirror\ApiBundle\Controller
 */
class SmsController extends BaseController {

    /**
     * 获取短信验证码
     * @Route("/code/{telephone}")
     * @Method("GET")
     * @param $telephone
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function codeAction($telephone) {
        $rr = $this->get('telephone_code_service')->sendCode($telephone);

        return $this->buildResponse($rr);
    }

    /**
     * 验证码列表
     * @Route("/list")
     * @Method("GET")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request) {
        $pageable = $this->getPage($request);
        $telephone = $request->get('telephone', '');
        $rr = $this->get("telephone_code_service")->getList($pageable, $telephone);

        return $this->buildResponse($rr);
    }

    /**
     * @Route("/check/code")
     * @Method("GET")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function checkCodeAction(Request $request){
        $telephone=$request->get('telephone','');
        $code=$request->get('code','');
        $rr=$this->get('telephone_code_service')->validateCode($telephone, $code);
        if($rr){
            $rr=$this->get('telephone_code_service')->completeValid($telephone, $code);
        }else{
            return $this->buildResponse(new ReturnResult(Code::$code_error));
        }
        return $this->buildResponse($rr);
    }
}
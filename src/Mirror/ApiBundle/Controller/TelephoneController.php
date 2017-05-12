<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/12
 * Time: 16:15
 */

namespace Mirror\ApiBundle\Controller;

use Mirror\ApiBundle\Common\Code;
use Mirror\ApiBundle\ViewModel\ReturnResult;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Mirror\ApiBundle\Util\TelephoneHelper;
/**
 * @Route("/telephone")
 * Class TelephoneController
 * @package Mirror\ApiBundle\Controller
 */
class TelephoneController extends BaseController
{
    /**
     * @Route()
     * @Method("GET")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function check(Request $request){
        $phone=$request->get('phone','');
        $result=TelephoneHelper::getResult($phone);
        $rr=new ReturnResult();
        if($result['error_code']!==0){
            $rr->errno=Code::$telephone_check_fault;
            $rr->errmsg=$result['reason'];
        }else{
            $rr->result=$result['result'];
        }
        return $this->buildResponse($rr);
    }
}
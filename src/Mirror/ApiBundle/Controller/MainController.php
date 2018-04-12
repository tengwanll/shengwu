<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/12/16
 * Time: 18:03
 */

namespace Mirror\ApiBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/main")
 * Class BoxController
 * @package Mirror\ApiBundle\Controller
 */
class MainController extends BaseController
{
    /**
     * @Route("/company")
     * @Method("GET")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getCompanyList(Request $request){
        $redis=$this->get('snc_redis.default');
        $rr=$this->get('main_service')->getCompanyList($redis);
        return $this->buildResponse($rr);
    }
}

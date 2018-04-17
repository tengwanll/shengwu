<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/5/27
 * Time: 10:03
 */

namespace Mirror\AdmBundle\Controller;

use Mirror\ApiBundle\Annotation\OAuth;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/server")
 * Class SortController
 * @package Mirror\AdmBundle\Controller
 */
class ServerController extends BaseController
{
    /**
     * @Route("")
     * @Template()
     * @return array
     */
    public function listAction(Request $request){
        $sortId=$request->get('sortId',1);
        return array('sortId'=>$sortId);
    }

    /**
     * @Route("/{id}",requirements={"id":"\d+"})
     * @Template()
     * @return array
     */
    public function infoAction($id){
        return array('id'=>$id);
    }

    /**
     * @Route("/add")
     * @Template()
     * @return array
     */
    public function addAction(){
        return array();
    }
}
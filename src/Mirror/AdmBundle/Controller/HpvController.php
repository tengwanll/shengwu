<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/12/14
 * Time: 17:32
 */

namespace Mirror\AdmBundle\Controller;

use Mirror\ApiBundle\Annotation\OAuth;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/hpv")
 * Class HpvController
 * @package Mirror\AdmBundle\Controller
 */
class HpvController extends Controller
{
    /**
     * @OAuth()
     * @Route("")
     * @Template
     * @return array
     */
    public function listAction(Request $request){
        $page=$request->get('page',1);
        return array('page'=>$page);
    }
}
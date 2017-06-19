<?php

namespace Mirror\AdmBundle\Controller;

use Mirror\ApiBundle\Annotation\OAuth;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/order")
 * Class OrderController
 * @package Mirror\MirrorAdmBundle\Controller
 */
class OrderController extends Controller
{
    /**
     * @OAuth()
     * @Route("")
     * @Template
     * @return array
     */
    public function listAction(Request $request)
    {
        $role=$request->getSession()->get('role',1);
        return array('role'=>$role);
    }

    /**
     * @OAuth()
     * @Route("/{id}",requirements={"id":"\d+"})
     * @Template
     * @param $id
     * @return array
     */
    public function orderInfoAction($id)
    {
        return array('id'=>$id);
    }
}

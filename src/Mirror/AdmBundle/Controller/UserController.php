<?php

namespace Mirror\AdmBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/user")
 * Class UserController
 * @package Mirror\MirrorAdmBundle\Controller
 */
class UserController extends Controller
{
    /**
     * 用户列表
     * @Route("")
     * @Template
     * @return array
     */
    public function userListAction(){
        return array();
    }
    /**
     * 添加用户
     * @Route("/add")
     * @Template
     * @return array
     */
    public function addUserAction()
    {
        return array();
    }

    /**
     * 用户详情
     * @Route("/{id}",requirements={"id":"\d+"})
     * @Template
     * @param $id
     * @return array
     */
    public function infoUserAction($id)
    {
        return array('id'=>$id);
    }
}

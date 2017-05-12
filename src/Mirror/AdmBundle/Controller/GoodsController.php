<?php

namespace Mirror\AdmBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
/**
 * @Route("/goods")
 * Class DataController
 * @package Mirror\MirrorAdmBundle\Controller
 */
class GoodsController extends Controller
{
    /**
     * @Route("")
     * @Template
     * @return array
     */
    public function dataAction()
    {
        return array();
    }
}

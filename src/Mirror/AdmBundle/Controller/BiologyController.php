<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/8/28
 * Time: 14:51
 */

namespace Mirror\AdmBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/biology")
 * Class BiologyController
 * @package Mirror\AdmBundle\Controller
 */
class BiologyController
{
    /**
     * @Route("")
     * @return array
     * @Template
     */
    public function getList(){
        return array();
    }

    /**
     * @Route("/add")
     * @return array
     * @Template
     */
    public function add(){
        return array();
    }

    /**
     * @Route("/edit")
     * @return array
     * @Template
     */
    public function edit(){
        return array();
    }
}
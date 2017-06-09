<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/6/1
 * Time: 13:56
 */

namespace Mirror\AdmBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/car")
 * Class CarController
 * @package Mirror\AdmBundle\Controller
 */
class CarController extends Controller
{
    /**
     * @Route("")
     * @Template()
     * @return array
     */
    public function listAction(){
        return array();
    }
    
}
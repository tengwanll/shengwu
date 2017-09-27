<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/9/26
 * Time: 10:05
 */

namespace Mirror\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/swoole")
 * Class SwooleController
 * @package Mirror\ApiBundle\Controller
 */
class SwooleController extends BaseController
{
    /**
     * @Route("")
     * @Method("GET")
     */
    public function start(){
        $this->get('swoole_service')->_prepare();
    }
}
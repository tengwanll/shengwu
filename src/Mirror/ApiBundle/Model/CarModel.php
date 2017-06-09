<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/6/1
 * Time: 14:13
 */

namespace Mirror\ApiBundle\Model;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("car_model",parent="base_model")
 * Class CarModel
 * @package Mirror\ApiBundle\Model
 */
class CarModel extends BaseModel
{
    private $repositoryName = 'MirrorApiBundle:GoodsCar';

    public function getRepositoryName() {
        return $this->repositoryName;
    }
}
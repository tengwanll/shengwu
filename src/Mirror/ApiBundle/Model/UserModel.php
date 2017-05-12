<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2016/8/8
 * Time: 9:56
 */

namespace Mirror\ApiBundle\Model;

use JMS\DiExtraBundle\Annotation\Service;
/**
 * @Service("user_model",parent="base_model")
 * Class UserModel
 * @package Mirror\ApiBundle\Model
 */
class UserModel extends BaseModel
{
    private $repositoryName = 'MirrorApiBundle:User';

    public function getRepositoryName() {
        return $this->repositoryName;
    }
}
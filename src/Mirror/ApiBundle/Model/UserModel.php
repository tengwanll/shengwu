<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2016/8/8
 * Time: 9:56
 */

namespace Mirror\ApiBundle\Model;

use JMS\DiExtraBundle\Annotation\Service;
use Mirror\ApiBundle\Common\Constant;
use Mirror\ApiBundle\Entity\User;

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

    /**
     * @param User $user
     * @return bool|User
     */
    public function add(User $user){
        $date=new \DateTime();
        $user->setPassword(md5($user->getPassword()));
        $user->setStatus(Constant::$status_normal);
        $user->setCreateTime($date);
        $user->setUpdateTime($date);
        if($this->save($user)){
            return $user;
        }else{
            return false;
        }

    }
}
<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/5/15
 * Time: 17:47
 */

namespace Mirror\ApiBundle\Model;

use JMS\DiExtraBundle\Annotation as DI;
use Mirror\ApiBundle\Common\Constant;
use Mirror\ApiBundle\Entity\Sort;

/**
 * @DI\Service("sort_model",parent="base_model")
 * Class SortModel
 * @package Mirror\ApiBundle\Model
 */
class SortModel extends BaseModel
{
    private $repositoryName = 'MirrorApiBundle:Sort';

    public function getRepositoryName() {
        return $this->repositoryName;
    }

    public function getList($name){
        
    }

    /**
     * @param $right
     * @return array
     */
    public function moveSortLeft($right){
        $dql='update '.$this->repositoryName.' u set u.leftR=u.leftR+2 where u.leftR >'.$right;
        $query = $this->getEntityManager()->createQuery($dql);
        return $query->getResult();
    }

    /**
     * @param $right
     * @return array
     */
    public function moveSortRight($right){
        $dql='update '.$this->repositoryName.' u set u.rightR=u.rightR+2 where u.rightR >='.$right;
        $query = $this->getEntityManager()->createQuery($dql);
        return $query->getResult();
    }

    /**
     * @param $right
     * @param $level
     * @param $parentId
     * @param $name
     * @param $image
     * @return mixed
     */
    public function add($right,$level,$parentId,$name,$image){
        $date=new \DateTime();
        $sort=new Sort();
        $sort->setName($name);
        $sort->setImage($image);
        $sort->setLeftR($right);
        $sort->setRightR($right+1);
        $sort->setLevel($level+1);
        $sort->setStatus(Constant::$status_normal);
        $sort->setParentId($parentId);
        $sort->setCreateTime($date);
        $sort->setUpdateTime($date);
        return $this->save($sort);
    }
}
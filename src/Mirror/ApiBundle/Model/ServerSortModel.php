<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/6/1
 * Time: 14:13
 */

namespace Mirror\ApiBundle\Model;

use JMS\DiExtraBundle\Annotation as DI;
use Mirror\ApiBundle\Common\Constant;
use Mirror\ApiBundle\Entity\ServerSort;

/**
 * @DI\Service("server_sort_model",parent="dbal_base_model")
 * Class CarModel
 * @package Mirror\ApiBundle\DbalBaseModel
 */
class ServerSortModel extends DbalBaseModel
{
    private $tableName = 'server_sort';

    public function getTableName() {
        return $this->tableName;
    }

    /**
     * @param $right
     * @param $sign
     * @return array
     */
    public function moveSortLeft($right,$sign='add'){
        if($sign=='add'){
            $dql='update '.$this->tableName.' u set u.left_r=u.left_r+2 where u.left_r >'.$right;
        }else{
            $dql='update '.$this->tableName.' u set u.left_r=u.left_r-2 where u.left_r >'.$right;
        }
        return $this->conn->executeQuery($dql);
    }

    /**
     * @param $right
     * @param $sign
     * @return array
     */
    public function moveSortRight($right,$sign='add'){
        if($sign=='add'){
            $dql='update '.$this->tableName.' u set u.right_r=u.right_r+2 where u.right_r >='.$right;
        }else{
            $dql='update '.$this->tableName.' u set u.right_r=u.right_r-2 where u.right_r >='.$right;
        }
        return $this->conn->executeQuery($dql);
    }

    public function getLeaf(){
        $dql = "select u from ".$this->getRepositoryName()." u where u.leftR+1=u.rightR";
        $query = $this->getEntityManager()->createQuery($dql);
        return $query->getResult();
    }
}
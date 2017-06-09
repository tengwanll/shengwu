<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/5/26
 * Time: 14:54
 */

namespace Mirror\ApiBundle\Model;

use JMS\DiExtraBundle\Annotation as DI;
use Mirror\ApiBundle\Common\Constant;
use Mirror\ApiBundle\Entity\SortAttr;

/**
 * @DI\Service("sort_attr_model",parent="base_model")
 * Class SortAttrModel
 * @package Mirror\ApiBundle\Model
 */
class SortAttrModel extends BaseModel
{
    private $repositoryName = 'MirrorApiBundle:SortAttr';

    public function getRepositoryName() {
        return $this->repositoryName;
    }

    public function add($attr,$sortId){
        $date=new \DateTime();
        $arr=array();
        foreach($attr as $name){
            $sortAttr=new SortAttr();
            $sortAttr->setName($name);
            $sortAttr->setStatus(Constant::$status_normal);
            $sortAttr->setCreateTime($date);
            $sortAttr->setUpdateTime($date);
            $sortAttr->setSortId($sortId);
            $arr[]=$sortAttr;
        }
        return $this->saveArray($arr);
    }
}
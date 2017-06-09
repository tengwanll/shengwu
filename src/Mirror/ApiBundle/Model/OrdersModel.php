<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/5/4
 * Time: 17:13
 */

namespace Mirror\ApiBundle\Model;

use Doctrine\ORM\Tools\Pagination\Paginator;
use JMS\DiExtraBundle\Annotation as DI;
use Mirror\ApiBundle\Util\QueryHelper;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @DI\Service("orders_model",parent="base_model")
 * Class OrderModel
 * @package Mirror\ApiBundle\Model
 */
class OrdersModel extends BaseModel
{
    private $repositoryName = 'MirrorApiBundle:Orders';

    public function getRepositoryName() {
        return $this->repositoryName;
    }

    /**
     * @param $pageable
     * @param $number
     * @param $beginTime
     * @param $endTime
     * @param $username
     * @param $status
     * @return Paginator
     */
    public function getList($pageable,$number,$beginTime,$endTime,$username,$status){
        $dql=" select o,u.username from MirrorApiBundle:orders o join MirrorApiBundle:user u ";
        $where=array();
        $arguments=array();
        $where[]=' o.userId=u.id ';
        if($number){
            $where[]=" o.orderNo like :orderNo ";
            $arguments['orderNo']='%'.$number.'%';
        }
        if($beginTime){
            $where[]=" o.createTime > :beginTime ";
            $arguments['beginTime']=new \DateTime($beginTime);
        }
        if($endTime){
            $where[]=" o.createTime < :endTime ";
            $arguments['endTime']=new \DateTime($endTime);
        }
        if($username){
            $where[]=" u.username like :username ";
            $arguments['username']='%'.$username.'%';
        }
        if($status!='all'){
            $where[]=" o.status = :status ";
            $arguments['status']=$status;
        }
        $dql=QueryHelper::makeQueryString($dql,$where);
        $query=$this->getEntityManager()->createQuery($dql);
        if($pageable){
            $query=QueryHelper::setPageInfo($query,$pageable);
        }
        $query->setParameters($arguments);
        return new Paginator($query);
    }
}
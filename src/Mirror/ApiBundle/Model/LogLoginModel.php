<?php
/**
 * Created by PhpStorm.
 * User: rpzhan
 * Date: 16/1/13
 * Time: 22:57
 */

namespace Mirror\ApiBundle\Model;

use JMS\DiExtraBundle\Annotation\Service;
use Mirror\ApiBundle\Common\Constant;
use Mirror\ApiBundle\Entity\LogLogin;
use Mirror\ApiBundle\Util\Helper;
use Mirror\ApiBundle\Util\QueryHelper;

/**
 * @Service("log_login_model", parent="base_model")
 * Class LogLoginModel
 * @package Mirror\ApiBundle\Model
 */
class LogLoginModel extends BaseModel {

    private $repositoryName = 'MirrorApiBundle:LogLogin';

    public function getRepositoryName() {
        return $this->repositoryName;
    }

    /**
     * 保存登录记录
     * @param $entityId
     * @param $type
     * @param $ip
     */
    public function saveLogin($entityId, $type, $ip) {
        $logLogin = new LogLogin();
        $logLogin->setEntityId($entityId);
        $logLogin->setType($type);
        $logLogin->setIp($ip);
        $logLogin->setStatus(Constant::$status_normal);
        $logLogin->setCreateTime(new \DateTime());
        $logLogin->setUpdateTime(new \DateTime());
        $this->save($logLogin);
    }

    /**
     * 使用信息统计
     * @param $pageable
     * @param $beginTime
     * @param $endTime
     * @param $place
     * @return array
     */
    public function getLoginInfo($pageable,$beginTime,$endTime,$place){
        $dql=' select e.place,count(l.entityId) as total,count(distinct l.entityId) as userTotal from MirrorApiBundle:LogLogin l,MirrorApiBundle:Equipment e ';
        $where=array();
        $where[]=' e.id=l.equipmentId and e.status=1 ';
        if($beginTime&&$endTime){
            $beginTime=Helper::createDate($beginTime.' 00:00:00');
            $endTime=Helper::createDate($endTime.' 23:59:59');
            $where[]=' l.createTime between :beginTime and :endTime ';
        }
        if($place){
            $where[]=' e.place like :place ';
        }
        $dql=QueryHelper::makeQueryString($dql,$where);
        $dql.=' group by e.place ';
        $query=$this->getEntityManager()->createQuery($dql);
        if($pageable){
            $query=QueryHelper::setPageInfo($query,$pageable);
        }
        if($beginTime&&$endTime){
            $query->setParameter('beginTime',$beginTime);
            $query->setParameter('endTime',$endTime);
        }
        if($place){
            $query->setParameter('place','%'.$place.'%');
        }
        return $query->getResult();
    }

    public function getLoginInfoAll($beginTime,$endTime,$place){
        $dql=' select count(DISTINCT e.place) from MirrorApiBundle:LogLogin l,MirrorApiBundle:Equipment e ';
        $where=array();
        $where[]=' e.id=l.equipmentId and e.status=1 ';
        if($beginTime&&$endTime){
            $beginTime=Helper::createDate($beginTime.' 00:00:00');
            $endTime=Helper::createDate($endTime.' 23:59:59');
            $where[]=' l.createTime between :beginTime and :endTime ';
        }
        if($place){
            $where[]=' e.place like :place ';
        }
        $dql=QueryHelper::makeQueryString($dql,$where);
        $query=$this->getEntityManager()->createQuery($dql);
        if($beginTime&&$endTime){
            $query->setParameter('beginTime',$beginTime);
            $query->setParameter('endTime',$endTime);
        }
        if($place){
            $query->setParameter('place','%'.$place.'%');
        }
        return $query->getSingleScalarResult();
    }

    public function getTotalToday($conn,$start,$end){
        $start="'".$start."'";
        $end="'".$end."'";
        $dql=" select HOUR(create_time) as hours,count(*) as num from log_login where create_time>$start and create_time<$end  group by hours ";


        return $conn->fetchAll($dql);
    }

    public function getTotal($conn){
        $dql=" select count(*) as num from log_login  ";


        return $conn->fetchAll($dql);
    }

    public function getTotalByWeek($conn,$time){
        $time="'".$time."'";
        $dql=" select WEEKDAY(create_time) as days,count(*) as num from log_login where  `create_time` between $time - INTERVAL WEEKDAY($time) DAY and $time + INTERVAL 7-WEEKDAY($time) DAY group by days";


        return $conn->fetchAll($dql);
    }

    public function getTotalByMonth($conn,$time){
        $time="'".$time."'";
        $day=date('t');
        $dql=" select DAY(create_time) as days,count(*) as num from log_login where  `create_time` between $time - INTERVAL DAY($time) DAY and $time + INTERVAL $day - DAY($time) DAY group by days";


        return $conn->fetchAll($dql);
    }


    public function getTotalByYear($conn,$time){
        $year=substr($time , 0 , 4);
        $time="'".$time."'";
        $dql=" select month ( create_time ) as months,count(*) as num from log_login where   year ( $time)=$year group by months";


        return $conn->fetchAll($dql);
    }

    public function getTotalCustom ($conn,$start,$end){
        $start="'".$start."'";
        $end="'".$end."'";
        $dql=" select DATE_FORMAT(create_time,'%Y-%m-%d') as days ,count(*) as num from log_login where create_time>$start and create_time<$end  group by days  ";


        return $conn->fetchAll($dql);
    }

    ////////////////////////////////////////////////////


    public function getUAByWeek($conn,$time){
        $time="'".$time."'";
        $dql=" select WEEKDAY(create_time) as days,count(DISTINCT entity_id) as num from log_login where type=1 and `create_time` between $time - INTERVAL WEEKDAY($time) DAY and $time + INTERVAL 7-WEEKDAY($time) DAY group by days";


        return $conn->fetchAll($dql);
    }

    public function getUAByMonth($conn,$time){
        $time="'".$time."'";
        $day=date('t');
        $dql=" select DAY(create_time) as days,count(DISTINCT entity_id) as num from log_login where type=1 and `create_time` between $time - INTERVAL DAY($time) DAY and $time + INTERVAL $day-WEEKDAY($time) DAY group by days";


        return $conn->fetchAll($dql);
    }

    public function getUAByYear($conn,$time){
        $year=substr($time , 0 , 4);
        $time="'".$time."'";
        $dql=" select month ( create_time ) as months,count(DISTINCT entity_id) as num from log_login where type=1 and  year ( $time)=$year group by months";


        return $conn->fetchAll($dql);
    }

    public function getUAByTotalCustom ($conn,$start,$end){
        $start="'".$start."'";
        $end="'".$end."'";
        $dql=" select DATE_FORMAT(create_time,'%Y-%m-%d') as days ,count(*) as num from log_login where type=1 and create_time>$start and create_time<$end  group by days  ";


        return $conn->fetchAll($dql);
    }

}
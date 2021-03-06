<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/12/15
 * Time: 16:07
 */

namespace Mirror\ApiBundle\Model;

use JMS\DiExtraBundle\Annotation as DI;
use Mirror\ApiBundle\Util\Helper;

/**
 * @DI\Service("box_model")
 * Class HpvModel
 * @package Mirror\ApiBundle\Model
 */
class BoxModel
{
    public function getList($uniqueId,$conn,$pageable){
        $sql="select * from weixin.box ";
        $where=array();
        if($uniqueId){
            $where[]='unique_id like "%'.$uniqueId.'%"';
        }
        $sql=Helper::makeQueryString($sql,$where);
        if($pageable){
            if ($pageable) {
                $page=$pageable->getPage();
                $rows=$pageable->getRows();
                $start=($page-1)*$rows;
                $sql.= " limit $start,$rows ";
            }
        }
        return $conn->fetchAll($sql);
    }

    public function getCount($uniqueId,$conn){
        $sql="select count(id) as total from weixin.box ";
        $where=array();
        if($uniqueId){
            $where[]='unique_id like "%'.$uniqueId.'%"';
        }
        $sql=Helper::makeQueryString($sql,$where);
        return $conn->fetchAll($sql);
    }

    public function getBoxDetail($id,$conn){
        $sql="select * from weixin.box where id=$id ";
        return $conn->fetchAll($sql);
    }

    public function getBoxInfoDetail($uniqueId,$conn){
        $sql="select * from weixin.box_info where box_id=$uniqueId ";
        return $conn->fetchAll($sql);
    }

    public function getBoxGeneDetail($uniqueId,$conn){
        $sql="select IL6,HLA_C,ZNF365,MMP1,AQP3,NQO1,SOD2,NFE2L2,CAT,MC1R,GSTP1,IRF4 from weixin.box_gene where box_id=$uniqueId ";
        return $conn->fetchAll($sql);
    }
}
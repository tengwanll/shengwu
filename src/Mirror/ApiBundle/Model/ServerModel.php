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
use Mirror\ApiBundle\Util\QueryHelper;

/**
 * @DI\Service("server_model",parent="dbal_base_model")
 * Class CarModel
 * @package Mirror\ApiBundle\DbalBaseModel
 */
class ServerModel extends DbalBaseModel
{
    private $tableName = 'server';

    public function getTableName() {
        return $this->tableName;
    }

    public function getList($parameters,$pageable,$sort,$left,$right,$name=''){
        $dql="select u.* from server u join server_sort s  ";
        $where = array();
        $where[]='u.sort_id = s.id';
        if($name){
            $where[]="u.name like '%$name%'";
        }
        foreach ($parameters as $key => $equals) {
            if (is_array($equals)) {
                foreach ($equals as $k => $value) {
                    $where[] = ' u.'.$k.' '.$key.' '.$value;
                }
            }
        }
        $dql = QueryHelper::makeQueryString($dql, $where);
        if($left&&$right){
            $dql.=' and s.right_r<= '.$right.' and s.left_r>= '.$left;
        }
        // 拼接sort语句
        if ($sort) {
            $dql .= ' order by u.'.$sort;
        }
        if ($pageable) {
            $page=$pageable->getPage();
            $rows=$pageable->getRows();
            $start=($page-1)*$rows;
            $dql.= " limit $start,$rows ";
        }
        return $this->conn->fetchAll($dql);
    }
}
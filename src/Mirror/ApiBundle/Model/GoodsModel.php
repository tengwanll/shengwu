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
 * @DI\Service("goods_model",parent="base_model")
 * Class OrderModel
 * @package Mirror\ApiBundle\Model
 */
class GoodsModel extends BaseModel
{
    private $repositoryName = 'MirrorApiBundle:Goods';

    public function getRepositoryName() {
        return $this->repositoryName;
    }

    public function getDetail($id,$conn){
        $sql='select name,sort_id,price,image,description,buy_num,status,create_time,update_time,column_json(attr) as attrs from goods where id='.$id;
        return $conn->fetchAssoc($sql);
    }

    /**
     * @param $name
     * @param $sortId
     * @param $price
     * @param $description
     * @param $attrs
     * @param $conn
     * @param $image
     * @return mixed
     */
    public function add($name,$sortId,$price,$description,$attrs,$conn,$image=0){
        $date=date("y-m-d H:i:s");
        $str='null';
        if($attrs){
            $attrs=explode(',',$attrs);
            foreach ($attrs as $attr){
                $str.="'".$attr."',";
            }
            $str='column_create('.trim($str,',').')';
        }
        $sql="insert into goods(`name`,`sort_id`,`price`,`image`,`description`,`attr`,`status`,`create_time`,`update_time`) VALUES('$name',$sortId,$price,$image,'$description',$str,1,'$date','$date')";
        $conn->exec($sql);
        return $conn->fetchAssoc('SELECT LAST_INSERT_ID() as goodsId');
    }

    /**
     * @param $parameters
     * @param $pageable
     * @param $sort
     * @param $left
     * @param $right
     * @return Paginator
     */
    public function getList($parameters,$pageable,$sort,$left,$right){
        $dql="select u from ".$this->getRepositoryName()." u join MirrorApiBundle:Sort s where u.sortId = s.id";
        $where = array();
        $arguments=array();
        $index=1;
        foreach ($parameters as $key => $equals) {
            if (is_array($equals)) {
                foreach ($equals as $k => $value) {
                    $indexValue='value'.$index;
                    $where[] = ' u.'.$k.' '.$key.' :'.$indexValue;
                    $arguments[$indexValue] = $value;
                    $index++;
                }
            } else {
                $indexValue='value'.$index;
                $where[] = ' u.'.$key.' = :'.$indexValue;
                $arguments[$indexValue] = $equals;
                $index++;
            }
        }
        $dql = QueryHelper::makeQueryString($dql, $where);
        if($left&&$right){
            $dql.=' and s.rightR<= '.$right.' and s.leftR>= '.$left;
        }
        // 拼接sort语句
        if ($sort) {
            $dql .= ' order by u.'.$sort;
        }
        $query = $this->getEntityManager()->createQuery($dql);
        if ($arguments && !empty($arguments)) {
            $query->setParameters($arguments);
        }
        if ($pageable) {
            $query = QueryHelper::setPageInfo($query, $pageable);
        }
        return new Paginator($query);
    }

    public function update($id,$name,$sortId,$price,$description,$attrs,$conn,$image=0){
        $str='null';
        if($attrs){
            $attrs=explode(',',$attrs);
            foreach ($attrs as $attr){
                $str.="'".$attr."',";
            }
            $str='column_create('.trim($str,',').')';
        }
        if($image){
            $image=',image='.$image;
        }
        $sql="update goods set name='$name',sort_id=$sortId,price=$price,description='$description',attr=$str".$image." where id=$id";
        return $conn->exec($sql);
    }
}
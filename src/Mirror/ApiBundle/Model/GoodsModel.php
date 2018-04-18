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
        $sql='select name,sort_id,price,image,description,buy_num,status,create_time,update_time,column_json(attr) as attrs,goods_number,unit,standard,vender from goods where id='.$id;
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
    public function add($name,$sortId,$price,$description,$attrs,$conn,$image,$goodsNumber,$unit,$standard,$vender){
        $date=date("y-m-d H:i:s");
        $str=null;
        if($attrs){
            $str='';
            $attrs=explode(',',$attrs);
            foreach ($attrs as $attr){
                $str.="'".$attr."',";
            }
            $str='column_create('.trim($str,',').')';
        }
        if(!$image){
            $image=0;
        }
        $sql="insert into goods(`name`,`sort_id`,`price`,`image`,`description`,`attr`,`goods_number`,`unit`,`standard`,`vender`,`status`,`create_time`,`update_time`) VALUES('$name',$sortId,$price,$image,'$description',$str,'$goodsNumber','$unit','$standard','$vender',1,'$date','$date')";
        $conn->exec($sql);
        return $conn->fetchAssoc('SELECT LAST_INSERT_ID() as goodsId');
    }

    /**
     * @param $parameters
     * @param $pageable
     * @param $sort
     * @param $left
     * @param $right
     * @param $attr
     * @param $conn
     * @return mixed
     */
    public function getList($parameters,$pageable,$sort,$left,$right,$attr,$name,$conn,$goodNumber){
        if($attr){
            $dql="select u.id,u.name,u.price,u.image,u.sort_id,u.status,s.name as sortName,column_get(u.attr,'$attr' as char) as myAttr,u.goods_number,u.unit,u.standard,u.vender from goods u join sort s  ";
        }else{
            $dql="select u.id,u.name,u.price,u.image,u.sort_id,u.status,s.name as sortName ,u.goods_number,u.unit,u.standard,u.vender from goods u join sort s  ";
        }
        $where = array();
        $where[]='u.sort_id = s.id';
        if($name){
            $where[]="u.name like '%$name%'";
        }
        if($goodNumber){
            $where[]="u.goods_number like '%$goodNumber%'";
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
        if($attr){
            $dql.=" and COLUMN_EXISTS(u.attr,'$attr') " ;
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
        return $conn->fetchAll($dql);
    }

    /**
     * @param $parameters
     * @param $pageable
     * @param $sort
     * @param $left
     * @param $right
     * @param $attr
     * @param $conn
     * @return mixed
     */
    public function getCount($parameters,$left,$right,$attr,$name,$conn,$goodNumber){
        $dql="select count(u.id) as total from goods u join sort s ";
        $where = array();
        $where[]='u.sort_id = s.id ';
        if($name){
            $where[]="u.name like '%$name%'";
        }
        if($goodNumber){
            $where[]="u.goods_number like '%$goodNumber%'";
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
        if($attr){
            $dql.=" and COLUMN_EXISTS(u.attr,'$attr') " ;
        }
        return $conn->fetchAll($dql);
    }

    public function update($id,$name,$sortId,$price,$description,$attrs,$conn,$image=0,$goodsNumber,$unit,$standard,$vender){
        $str='null';
        if($attrs){
            $str='';
            $attrs=explode(',',$attrs);
            foreach ($attrs as $attr){
                $str.="'".$attr."',";
            }
            $str='column_create('.trim($str,',').')';
        }
        if($image){
            $image=',image='.$image;
        }
        $sql="update goods set name='$name',sort_id=$sortId,price=$price,description='$description',goods_number='$goodsNumber',unit='$unit',standard='$standard',vender='$vender',attr=$str".$image." where id=$id";
        return $conn->exec($sql);
    }
}
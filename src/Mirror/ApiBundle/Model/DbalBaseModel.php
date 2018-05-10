<?php

namespace Mirror\ApiBundle\Model;

use Doctrine\DBAL\Connection;
use JMS\DiExtraBundle\Annotation\Service;
use Mirror\ApiBundle\Util\QueryHelper;
use Mirror\ApiBundle\ViewModel\Pageable;

/**
 * Class DbalBaseModel
 * @package Mirror\ApiBundle\Model
 */
abstract class DbalBaseModel {

    protected $conn;

    public function __construct(Connection $conn)
    {
        $this->conn=$conn;
    }

    public function beginTransaction(){
        $this->conn->beginTransaction();
    }

    public function commit(){
        $this->conn->commit();
    }

    public function rollback(){
        $this->conn->rollBack();
    }

    /**
     * 获取实体类名称
     * @return mixed
     */
    public abstract function getTableName();


    /**
     * 根据id获取
     * @param $id
     * @return int|null|object
     */
    public function getById($id) {
        $result=$this->conn->fetchAll('select * from '.$this->getTableName().' where id = :id',array('id'=>$id));
        if(isset($result[0])){
            return $result[0];
        }else{
            return array();
        }
    }

    /**
     * 删除
     * @param array $parameters
     * @return mixed
     */
    public function delete(array $parameters) {
        $where = array();
        $arguments = array();
        $index=1;
        $dql='delete from '.$this->getTableName();
        foreach ($parameters as $key => $equals) {
            if (is_array($equals)) {
                foreach ($equals as $k => $value) {
                    if($key=='in'){
                        $where[] = ' '.$k.' in('.$value.')';
                    }else{
                        $indexValue='value'.$index;
                        $where[] = ' '.$k.' '.$key.' :'.$indexValue;
                        $arguments[$indexValue] = $value;
                        $index++;
                    }
                }
            } else {
                $indexValue='value'.$index;
                $where[] = ' '.$key.' = :'.$indexValue;
                $arguments[$indexValue] = $equals;
                $index++;
            }
        }
        $dql = QueryHelper::makeQueryString($dql, $where);
        return $this->conn->executeUpdate($dql,$arguments);
    }


    /**
     * 修改
     * @param $fields
     * @param array $parameters
     * @return mixed
     */
    public function update($fields,array $parameters) {
        $where = array();
        $arguments = array();
        $index=1;
        $dql='update '.$this->getTableName().' set '.$fields;
        foreach ($parameters as $key => $equals) {
            if (is_array($equals)) {
                foreach ($equals as $k => $value) {
                    if($key=='in'){
                        $where[] = ' '.$k.' in('.$value.')';
                    }else{
                        $indexValue='value'.$index;
                        $where[] = ' '.$k.' '.$key.' :'.$indexValue;
                        $arguments[$indexValue] = $value;
                        $index++;
                    }
                }
            } else {
                $indexValue='value'.$index;
                $where[] = ' '.$key.' = :'.$indexValue;
                $arguments[$indexValue] = $equals;
                $index++;
            }
        }
        $dql = QueryHelper::makeQueryString($dql, $where);
        return $this->conn->executeUpdate($dql,$arguments);
    }

    /**
     * 添加
     * @param array $parameters
     * @return bool|int
     */
    public function save(array $parameters){
        return $this->conn->insert($this->getTableName(),$parameters);
    }

    /**
     * 根据属性查询单个
     * @param $property
     * @param $value
     * @return null|object
     */
    public function getOneByProperty($property, $value) {
        $dql='select * from '.$this->getTableName().' where '.$property.' = :value limit 0,1';
        $result=$this->conn->fetchAll($dql,array('value'=>$value));
        if(isset($result[0])){
            return $result[0];
        }else{
            return array();
        }
    }

    /**
     * 根据属性查询集合
     * @param $property
     * @param $value
     * @return array
     */
    public function getByProperty($property, $value) {
        return $this->conn->fetchAll('select * from '.$this->getTableName().' where '.$property.' = :value',array('value'=>$value));
    }


    /**
     * 查询所有
     * @return array
     */
    public function getAll() {
        return $this->conn->fetchAll('select * from '.$this->getTableName());
    }

    /**
     * 分页
     * @param $name
     * @param $value
     * @param string $sort
     * @param Pageable|null $pageable
     * @return mixed
     */
    public function getByPage($name, $value,$pageable=null, $sort = null) {
        $dql='select * from  '.$this->getTableName().' where '.$name.' = :value';
        if ($sort) {
            $dql .= ' order by '.$sort;
        }
        if($pageable){
            $page=$pageable->getPage();
            $rows=$pageable->getRows();
            $start=($page-1)*$rows;
            $dql.= " limit $start,$rows ";
        }

        return $this->conn->fetchAll($dql,array('value'=>$value));


    }

    /**
     * 分页
     * @param array $parameters
     * @param string $sort
     * @param Pageable|null $pageable
     * @return mixed
     */
    public function getByPages($parameters=array(),$pageable=null, $sort = null ) {
        $dql='select * from  '.$this->getTableName();
        $where = array();
        $arguments = array();
        $index=1;
        foreach ($parameters as $key => $equals) {
            if (is_array($equals)) {
                foreach ($equals as $k => $value) {
                    if($key=='in'){
                        $where[] = ' '.$k.' in('.$value.')';
                    }else{
                        $indexValue='value'.$index;
                        $where[] = ' '.$k.' '.$key.' :'.$indexValue;
                        $arguments[$indexValue] = $value;
                        $index++;
                    }
                }
            } else {
                $indexValue='value'.$index;
                $where[] = ' '.$key.' = :'.$indexValue;
                $arguments[$indexValue] = $equals;
                $index++;
            }
        }
        $dql = QueryHelper::makeQueryString($dql, $where);
        if ($sort) {
            $dql .= ' order by '.$sort;
        }
        if($pageable){
            $page=$pageable->getPage();
            $rows=$pageable->getRows();
            $start=($page-1)*$rows;
            $dql.= " limit $start,$rows ";
        }
        return $this->conn->fetchAll($dql,$arguments);
    }

    /**
     * 算总数
     * @param $parameters
     * @return mixed
     */
    public function getCountBy($parameters) {
        $dql = "select count(id) as total from ".$this->getRepositoryName();
        $where = array();
        $arguments = array();
        $index=1;
        foreach ($parameters as $key => $equals) {
            if (is_array($equals)) {
                foreach ($equals as $k => $value) {
                    if($key=='in'){
                        $where[] = ' '.$k.' in('.$value.')';
                    }else{
                        $indexValue='value'.$index;
                        $where[] = ' '.$k.' '.$key.' :'.$indexValue;
                        $arguments[$indexValue] = $value;
                        $index++;
                    }
                }
            } else {
                $indexValue='value'.$index;
                $where[] = ' '.$key.' = :'.$indexValue;
                $arguments[$indexValue] = $equals;
                $index++;
            }
        }
        $dql = QueryHelper::makeQueryString($dql, $where);
        $result=$this->conn->fetchAll($dql,$arguments);
        return $result[0]['total'];
    }

}
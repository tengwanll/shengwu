<?php

namespace Mirror\ApiBundle\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator;
use JMS\DiExtraBundle\Annotation\Service;
use Mirror\ApiBundle\Util\QueryHelper;
use Mirror\ApiBundle\ViewModel\Pageable;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class BaseModel
 * @package Mirror\ApiBundle\Model
 */
abstract class BaseModel {

    /** @var $entityManager \Doctrine\ORM\EntityManager */
    private $entityManager;
    private $validator;

    public function __construct(ValidatorInterface $validator,EntityManager $entityManager)
    {
        $this->validator=$validator;
        $this->entityManager = $entityManager;
    }

    /**
     * @return ValidatorInterface
     */
    public function getValidator(){
        return $this->validator;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager() {
        return $this->entityManager;
    }

    /**
     * 获取实体类名称
     * @return mixed
     */
    public abstract function getRepositoryName();

    /**
     * 返回 EntityRepository
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getEntityRepository() {
        return $this->entityManager->getRepository($this->getRepositoryName());
    }

    /**
     * 根据id获取
     * @param $id
     * @return int|null|object
     */
    public function getById($id) {
        return $this->getByIdFromDb($id);
    }

    private function getByIdFromDb($id) {
        if (!$id) {
            return 0;
        }

        return $this->getEntityRepository()->find($id);
    }

   
    /**
     * 删除
     * @param $entity
     * @param bool|true $flush
     * @return mixed
     */
    public function delete($entity, $flush = true) {
        if ($entity) {
            $this->getEntityManager()->remove($entity);
            if ($flush) {

                $this->getEntityManager()->flush();
            }
        }

        return $entity;
    }

    /**
     * 删除集合
     * @param $arr
     * @param bool|true $flush
     * @return int
     */
    public function deleteArray($arr, $flush = true) {
        foreach ($arr as $a) {
            $this->getEntityManager()->remove($a);
        }
        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return 0;
    }

    /**
     * 保存
     * @param $entity
     * @param bool|true $flush
     * @return mixed
     */
    public function save($entity, $flush = true) {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $entity;
    }

    /**
     * 保存集合
     * @param $arr
     * @return int
     */
    public function saveArray($arr) {
        foreach ($arr as $a) {
            $this->getEntityManager()->persist($a);
        }
        $this->getEntityManager()->flush();

        return 0;
    }

    /**
     * 根据属性查询单个
     * @param $property
     * @param $value
     * @return null|object
     */
    public function getOneByProperty($property, $value) {
        $criteria = array();
        $criteria = array_merge(
            $criteria,
            array(
                $property => $value,
            )
        );

        return $this->getOneByCriteria($criteria);
    }

    /**
     * @param array $criteria
     * @return null|object
     */
    public function getOneByCriteria(array $criteria) {
        return $this->getEntityRepository()->findOneBy($criteria);
    }

    /**
     * 根据属性查询集合
     * @param $property
     * @param $value
     * @return array
     */
    public function getByProperty($property, $value) {
        $criteria = array();
        $criteria = array_merge(
            $criteria,
            array(
                $property => $value,
            )
        );

        return $this->getByCriteria($criteria);
    }

    /**
     * @param array $criteria
     * @return array
     */
    public function getByCriteria(array $criteria) {
        return $this->getEntityRepository()->findBy($criteria);
    }

    /**
     * 查询所有
     * @return array
     */
    public function getAll() {
        return $this->getEntityRepository()->findAll();
    }

    /**
     * 刷新
     * @param null $entity
     */
    public function flush($entity = null) {
        if ($entity) {
            $this->getEntityManager()->flush($entity);
        } else {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * 返回Query语句
     * @param string $dql
     * @return \Doctrine\ORM\Query
     */
    public function createQuery($dql = '') {
        return $this->getEntityManager()->createQuery($dql);
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
        $dql = "select u from ".$this->getRepositoryName()." u";
        $where[] = ' u.'.$name.'=:value ';
        $dql = QueryHelper::makeQueryString($dql, $where);
        if ($sort) {
            $dql .= ' order by u.'.$sort;
        }
        $query = $this->getEntityManager()->createQuery($dql);
        if ($pageable) {
            $query = QueryHelper::setPageInfo($query, $pageable);
        }
        $query = QueryHelper::setParameter($query, 'value', $value);

        return $query->getResult();
    }

    /**
     * 分页
     * @param array $parameters
     * @param string $sort
     * @param Pageable|null $pageable
     * @return mixed
     */
    public function getByPages($parameters=array(),$pageable=null, $sort = null ) {
        $dql = "select u from ".$this->getRepositoryName()." u";
        $where = array();
        $arguments = array();
        $index=1;
        foreach ($parameters as $key => $equals) {
            if (is_array($equals)) {
                foreach ($equals as $k => $value) {
                    if($key=='in'){
                        $where[] = ' u.'.$k.' in('.$value.')';
                    }else{
                        $indexValue='value'.$index;
                        $where[] = ' u.'.$k.' '.$key.' :'.$indexValue;
                        $arguments[$indexValue] = $value;
                        $index++;
                    }
                }
            } else {
                $indexValue='value'.$index;
                $where[] = ' u.'.$key.' = :'.$indexValue;
                $arguments[$indexValue] = $equals;
                $index++;
            }
        }
        $dql = QueryHelper::makeQueryString($dql, $where);
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

        return $query->getResult();
    }

    /**
     * 算总数
     * @param $wheres
     * @return mixed
     */
    public function getCountBy($parameters) {
        $dql = "select count(u) from ".$this->getRepositoryName()." u";
        $where = array();
        $arguments = array();
        $index=1;
        foreach ($parameters as $key => $equals) {
            if (is_array($equals)) {
                foreach ($equals as $k => $value) {
                    if($key=='in'){
                        $where[] = ' u.'.$k.' in('.$value.')';
                    }else{
                        $indexValue='value'.$index;
                        $where[] = ' u.'.$k.' '.$key.' :'.$indexValue;
                        $arguments[$indexValue] = $value;
                        $index++;
                    }
                }
            } else {
                $indexValue='value'.$index;
                $where[] = ' u.'.$key.' = :'.$indexValue;
                $arguments[$indexValue] = $equals;
                $index++;
            }
        }
        $dql = QueryHelper::makeQueryString($dql, $where);
        $query = $this->getEntityManager()->createQuery($dql);
        if ($arguments && !empty($arguments)) {
            $query->setParameters($arguments);
        }

        return (integer)$query->getSingleScalarResult();
    }

    /**
     * 查询
     * @param array $parameters
     * @param string $pageable
     * @param string $sort
     * @param string $fields
     * @return \Doctrine\ORM\Tools\Pagination\Paginator
     */
    public function getByParams($parameters = array(), $pageable = '', $sort = '',$fields='') {
        // 基础语句
        if($fields){
            $dql = "select $fields from ".$this->getRepositoryName()." u";
        }else{
            $dql = "select u from ".$this->getRepositoryName()." u";
        }
        $where = array();
        $arguments = array();
        $index=1;
        foreach ($parameters as $key => $equals) {
            if (is_array($equals)) {
                foreach ($equals as $k => $value) {
                    if($key=='in'){
                        $where[] = ' u.'.$k.' in('.$value.')';
                    }else{
                        $indexValue='value'.$index;
                        $where[] = ' u.'.$k.' '.$key.' :'.$indexValue;
                        $arguments[$indexValue] = $value;
                        $index++;
                    }
                }
            } else {
                $indexValue='value'.$index;
                $where[] = ' u.'.$key.' = :'.$indexValue;
                $arguments[$indexValue] = $equals;
                $index++;
            }
        }
        $dql = QueryHelper::makeQueryString($dql, $where);
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

    public function getFirstData($parameters = array(),$sort=null) {
        // 基础语句
        $dql = "select u from ".$this->getRepositoryName()." u";
        $where = array();
        $arguments = array();
        foreach ($parameters as $key => $equals) {
            if (is_array($equals)) {
                foreach ($equals as $k => $value) {
                    $where[] = ' u.'.$k.' '.$key.' :'.$k;
                    $arguments[$k] = $value;
                }
            } else {
                $where[] = ' u.'.$key.' = :'.$key;
                $arguments[$key] = $equals;
            }
        }
        $dql = QueryHelper::makeQueryString($dql, $where);
        // 拼接sort语句
        if ($sort) {
            $dql .= ' order by u.'.$sort;
        }
        $query = $this->getEntityManager()->createQuery($dql);
        if ($arguments && !empty($arguments)) {
            $query->setParameters($arguments);
        }

        return $query->getFirstResult();
    }
}
<?php

namespace Mirror\ApiBundle\Util;

use Doctrine\ORM\Query;
use Mirror\ApiBundle\ViewModel\Pageable;

class QueryHelper {

    public static function makeQueryString($dql, $where = array()) {
        if (count($where) == 0) {
            return $dql;
        }
        $whereString = $where [0];
        for ($i = 1; $i < count($where); $i++) {
            $whereString = $whereString.' and '.$where [$i];
        }

        return $dql.' where '.$whereString;
    }

    public static function setPageInfo(Query $query, Pageable $pageable) {
        if ($pageable) {
            $size = $pageable->getRows();
            $page = $pageable->getPage();
            $query->setFirstResult($size * ($page - 1));
            $query->setMaxResults($size);
        }

        return $query;
    }

    public static function setPageInfoDBAL($dql,Pageable $pageable){
        if ($pageable) {
            $size = $pageable->getRows();
            $page = $pageable->getPage();
            $dql.=' limit '.$size * ($page - 1).','.$size;
        }

        return $dql;
    }

    /**
     * @param Query $query
     * @param $key
     * @param $value
     * @return \Doctrine\ORM\Query
     */
    public static function setParameter(Query $query, $key, $value) {
        return $query->setParameter($key, $value);
    }
}

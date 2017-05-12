<?php

namespace Mirror\ApiBundle\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Base;
use JMS\DiExtraBundle\Annotation\Service;
use Mirror\ApiBundle\Util\Helper;

/**
 * @Service("dict_key_model", parent="base_model")
 * Class DictKeyModel
 * @package Mirror\ApiBundle\Model
 */
class DictKeyModel extends BaseModel {

    private $repositoryName = 'MirrorApiBundle:DictKey';

    public function getRepositoryName() {
        return $this->repositoryName;
    }

    public function search($arguments) {
        $pageable = Helper::getc($arguments, 'pageable', null);
        $dql = '
				select dk 
				from MirrorApiBundle:DictKey dk 
				order by dk.id
				';
        $query = $this->getEntityManager()->createQuery($dql);
        if ($pageable) {
            $size = $pageable->getRows();
            $page = $pageable->getPage();
            $query->setFirstResult($size * ($page - 1));
            $query->setMaxResults($size);
        }

        return $query->getResult();
    }

    public function getTotalCount() {
        $dql = '
				select count(dk) 
				from MirrorApiBundle:DictKey dk
				';
        $query = $this->getEntityManager()->createQuery($dql);

        return $query->getSingleScalarResult();
    }
}
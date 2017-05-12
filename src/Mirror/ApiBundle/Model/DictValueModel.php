<?php

namespace Mirror\ApiBundle\Model;

use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation\Service;
use Mirror\ApiBundle\Common\Constant;
use Mirror\ApiBundle\Entity\DictValue;
use Mirror\ApiBundle\Util\Helper;

/**
 * @Service("dict_value_model", parent="base_model")
 * Class DictValueModel
 * @package Mirror\ApiBundle\Model
 */
class DictValueModel extends BaseModel {

    private $repositoryName = 'MirrorApiBundle:DictValue';

    public function getRepositoryName() {
        return $this->repositoryName;
    }

    /**
     * 保存数据字典
     * @param $keyId
     * @param $showValue
     * @param $description
     * @return \Mirror\ApiBundle\Entity\DictValue
     */
    public function createValue($keyId, $showValue, $description) {
        $value = $this->getMax($keyId);
        if ($value) {
            $value++;
        } else {
            $value = 1;
        }
        $dictValue = new DictValue();
        $dictValue->setKeyId($keyId);
        $dictValue->setShowValue($showValue);
        $dictValue->setValue($value);
        $dictValue->setDescription($description);
        $dictValue->setStatus(Constant::$FIELD_STATUS_NORMAL);
        $dictValue->setCreateTime(new \DateTime());
        $dictValue->setUpdateTime(new \DateTime());

        $this->save($dictValue);

        return $dictValue;
    }

    /**
     * 根据value查询数据
     * @param $keyId
     * @param $showValue
     * @return \Mirror\ApiBundle\Entity\DictValue
     */
    public function findByShowValue($keyId, $showValue) {
        $params = array();
        if ($keyId != 0) {
            $params = array_merge(
                $params,
                array(
                    'keyId' => $keyId,
                )
            );
        }
        if ($showValue != '') {
            $params = array_merge(
                $params,
                array(
                    'value' => $showValue,
                )
            );
        }

        return $this->getOneByCriteria($params);
    }

    /**
     * 根据key获取value的最大值
     * @param $keyId
     * @return mixed
     */
    public function getMax($keyId) {
        $dql = 'select max(dv.value)
				from MirrorApiBundle:DictValue dv 
				where dv.keyId = '.$keyId;
        $query = $this->getEntityManager()->createQuery($dql);

        return $query->getSingleScalarResult();
    }
}
<?php

namespace Mirror\ApiBundle\Model;

use JMS\DiExtraBundle\Annotation\Service;

/**
 * @Service("telephone_model", parent="base_model")
 * Class TelephoneCodeModel
 * @package Mirror\ApiBundle\Model
 */
class TelephoneCodeModel extends BaseModel {

    private $repositoryName = 'MirrorApiBundle:TelephoneCode';

    public function getRepositoryName() {
        return $this->repositoryName;
    }
}
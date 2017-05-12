<?php

namespace Mirror\ApiBundle\Model;

use JMS\DiExtraBundle\Annotation\Service;
use Mirror\ApiBundle\Entity\ErrorDefinitions;
use Mirror\ApiBundle\Util\Helper;

/**
 * @Service("error_definition_model", parent="base_model")
 * Class ErrorDefinitionModel
 * @package Mirror\ApiBundle\Model
 */
class ErrorDefinitionModel extends BaseModel {

    private $repositoryName = 'MirrorApiBundle:ErrorDefinitions';

    public function getRepositoryName() {
        return $this->repositoryName;
    }

    public function retrieve($arguments) {
        $errorCode = Helper::getc($arguments, 'errorCode', 0);

        $errorDefinition = $this->em->getRepository('MirrorApiBundle:ErrorDefinitions')->findOneByErrorCode($errorCode);

        return $errorDefinition;
    }

    public function create($arguments) {
        $errorCode = Helper::getc($arguments, 'errorCode', 0);
        $errorMessage = Helper::getc($arguments, 'errorMessage', '');
        $status = Helper::getc($arguments, 'status', 0);
        if ($errorCode == 0) {
            return 0;
        }
        $now = new \DateTime();
        $error = new ErrorDefinitions;
        $error->setErrorCode($errorCode);
        $error->setErrorMessage($errorMessage);
        $error->setStatus($status);
        $error->setCreationDate($now);
        $error->setUpdatedDate($now);
        $this->save($error);

        return 0;
    }
}
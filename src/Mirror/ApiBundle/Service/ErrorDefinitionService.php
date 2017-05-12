<?php

namespace Mirror\ApiBundle\Service;

use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use Mirror\ApiBundle\Model\ErrorDefinitionModel;
use Mirror\ApiBundle\ViewModel\ReturnResult;

/**
 * @Service("error_definition_service")
 * Class ErrorDefinitionService
 * @package Mirror\ApiBundle\Service
 */
class ErrorDefinitionService {
    
    private $errorDefinitionModel;

    /**
     * @InjectParams({
     *      "errorDefinitionModel" = @Inject("error_definition_model")
     * })
     * ErrorDefinitionService constructor.
     * @param ErrorDefinitionModel $errorDefinitionModel
     */
    public function __construct(ErrorDefinitionModel $errorDefinitionModel) {
        $this->errorDefinitionModel = $errorDefinitionModel;
    }

    public function findByErrorCode($errNo, $default = '') {
        $ed = $this->getByCode($errNo);
        $messageCn = $ed ? $ed->getErrorMessage() : $default;

        return $messageCn;
    }

    private function getByCode($errNo) {

        $ed = $this->errorDefinitionModel->getOneByProperty('errorCode', (int)$errNo);

        return $ed;
    }

    public function create($errorCode, $errorMessage, $status) {
        $rr = new ReturnResult();

        $arguments = array(
            'errorCode' => $errorCode,
            'errorMessage' => $errorMessage,
            'status' => $status,
        );

        $ed = $this->getByCode($errorCode);
        if ($ed) {
            $rr->errno = 27400;

            return $rr;
        }

        $this->errorDefinitionModel->create($arguments);

        return $rr;
    }
}

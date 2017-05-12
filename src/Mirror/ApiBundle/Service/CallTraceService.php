<?php

namespace Mirror\ApiBundle\Service;

use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use Mirror\ApiBundle\Model\CallTraceModel;

/**
 * @Service("calltrace_service")
 * Class CallTraceService
 * @package Mirror\ApiBundle\Service
 */
class CallTraceService {

    private $callTraceModel;

    /**
     * @InjectParams({
     *      "callTraceModel" = @Inject("calltrace_model")
     * })
     * CallTraceService constructor.
     * @param CallTraceModel $callTraceModel
     */
    public function __construct(CallTraceModel $callTraceModel) {
        $this->callTraceModel=$callTraceModel;
    }

    public function create($apiName, $session, $request, $response) {
        $arguments = array(
            'apiName' => $apiName,
            'session' => $session,
            'request' => $request,
            'response' => $response,
        );
        $this->callTraceModel->create($arguments);
    }
}
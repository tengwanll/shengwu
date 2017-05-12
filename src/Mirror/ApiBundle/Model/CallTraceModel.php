<?php

namespace Mirror\ApiBundle\Model;

use JMS\DiExtraBundle\Annotation\Service;
use Mirror\ApiBundle\Entity\CallTraces;
use Mirror\ApiBundle\Util\Helper;

/**
 * @Service("calltrace_model", parent="base_model")
 * Class CallTraceModel
 * @package Mirror\ApiBundle\Model
 */
class CallTraceModel extends BaseModel {

    private $repositoryName = 'MirrorApiBundle:CallTrace';

    public function getRepositoryName() {
        return $this->repositoryName;
    }

    public function retrieve($arguments) {
        return 0;
    }

    public function create($arguments) {
        $apiName = Helper::getc($arguments, 'apiName', '');
        $session = Helper::getc($arguments, 'session', '');
        $request = Helper::getc($arguments, 'request', '');
        $response = Helper::getc($arguments, 'response', '');
        $callTrace = new CallTraces ();
        $callTrace->setApi($apiName);
        $callTrace->setSession($session);
        $callTrace->setRequest($request);
        $callTrace->setResponse($response);
        $callTrace->setCreateTime(new \DateTime());
        $callTrace->setUpdateTime(new \DateTime());
        $callTrace->setStatus(0);
        $this->save($callTrace);
    }
}
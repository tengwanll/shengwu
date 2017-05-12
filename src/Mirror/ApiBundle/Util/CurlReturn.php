<?php
/**
 * Created by PhpStorm.
 * User: tengwanll
 * Date: 2016/1/15
 * Time: 11:20
 */

namespace Mirror\ApiBundle\Util;


class CurlReturn
{
    private $result;
    private $status;
    private $errInfo;

    /**
     * CurlReturn constructor.
     * @param $result
     * @param $status
     * @param $errInfo
     */
    public function __construct($result, $status, $errInfo)
    {
        $this->result = $result;
        $this->status = $status;
        $this->errInfo = $errInfo;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getErrInfo()
    {
        return $this->errInfo;
    }

    /**
     * @param mixed $errInfo
     */
    public function setErrInfo($errInfo)
    {
        $this->errInfo = $errInfo;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

}
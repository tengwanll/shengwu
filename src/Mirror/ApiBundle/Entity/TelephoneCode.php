<?php

namespace Mirror\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TelephoneCode
 *
 * @ORM\Table(name="telephone_code")
 * @ORM\Entity
 */
class TelephoneCode
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=11, nullable=false)
     */
    private $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=6, nullable=false)
     */
    private $code;

    /**
     * @var integer
     *
     * @ORM\Column(name="validity", type="integer", nullable=true)
     */
    private $validity = 'NULL';

    /**
     * @var integer
     *
     * @ORM\Column(name="valid_begin_time", type="integer", nullable=true)
     */
    private $validBeginTime = 'NULL';

    /**
     * @var integer
     *
     * @ORM\Column(name="valid_end_time", type="integer", nullable=true)
     */
    private $validEndTime = 'NULL';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="valid_time", type="datetime", nullable=true)
     */
    private $validTime = 'NULL';

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status = 'NULL';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_time", type="datetime", nullable=false)
     */
    private $createTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_time", type="datetime", nullable=false)
     */
    private $updateTime = 'current_timestamp()';



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     *
     * @return TelephoneCode
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return TelephoneCode
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set validity
     *
     * @param integer $validity
     *
     * @return TelephoneCode
     */
    public function setValidity($validity)
    {
        $this->validity = $validity;

        return $this;
    }

    /**
     * Get validity
     *
     * @return integer
     */
    public function getValidity()
    {
        return $this->validity;
    }

    /**
     * Set validBeginTime
     *
     * @param integer $validBeginTime
     *
     * @return TelephoneCode
     */
    public function setValidBeginTime($validBeginTime)
    {
        $this->validBeginTime = $validBeginTime;

        return $this;
    }

    /**
     * Get validBeginTime
     *
     * @return integer
     */
    public function getValidBeginTime()
    {
        return $this->validBeginTime;
    }

    /**
     * Set validEndTime
     *
     * @param integer $validEndTime
     *
     * @return TelephoneCode
     */
    public function setValidEndTime($validEndTime)
    {
        $this->validEndTime = $validEndTime;

        return $this;
    }

    /**
     * Get validEndTime
     *
     * @return integer
     */
    public function getValidEndTime()
    {
        return $this->validEndTime;
    }

    /**
     * Set validTime
     *
     * @param \DateTime $validTime
     *
     * @return TelephoneCode
     */
    public function setValidTime($validTime)
    {
        $this->validTime = $validTime;

        return $this;
    }

    /**
     * Get validTime
     *
     * @return \DateTime
     */
    public function getValidTime()
    {
        return $this->validTime;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return TelephoneCode
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set createTime
     *
     * @param \DateTime $createTime
     *
     * @return TelephoneCode
     */
    public function setCreateTime($createTime)
    {
        $this->createTime = $createTime;

        return $this;
    }

    /**
     * Get createTime
     *
     * @return \DateTime
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }

    /**
     * Set updateTime
     *
     * @param \DateTime $updateTime
     *
     * @return TelephoneCode
     */
    public function setUpdateTime($updateTime)
    {
        $this->updateTime = $updateTime;

        return $this;
    }

    /**
     * Get updateTime
     *
     * @return \DateTime
     */
    public function getUpdateTime()
    {
        return $this->updateTime;
    }
}

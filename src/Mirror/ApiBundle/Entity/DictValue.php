<?php

namespace Mirror\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DictValue
 *
 * @ORM\Table(name="dict_value", indexes={@ORM\Index(name="keyid", columns={"key_id"})})
 * @ORM\Entity
 */
class DictValue
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
     * @var integer
     *
     * @ORM\Column(name="key_id", type="integer", nullable=false)
     */
    private $keyId;

    /**
     * @var integer
     *
     * @ORM\Column(name="value", type="integer", nullable=false)
     */
    private $value;

    /**
     * @var string
     *
     * @ORM\Column(name="show_value", type="string", length=255, nullable=false)
     */
    private $showValue;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description = 'NULL';

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    private $status;

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
     * Set keyId
     *
     * @param integer $keyId
     *
     * @return DictValue
     */
    public function setKeyId($keyId)
    {
        $this->keyId = $keyId;

        return $this;
    }

    /**
     * Get keyId
     *
     * @return integer
     */
    public function getKeyId()
    {
        return $this->keyId;
    }

    /**
     * Set value
     *
     * @param integer $value
     *
     * @return DictValue
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return integer
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set showValue
     *
     * @param string $showValue
     *
     * @return DictValue
     */
    public function setShowValue($showValue)
    {
        $this->showValue = $showValue;

        return $this;
    }

    /**
     * Get showValue
     *
     * @return string
     */
    public function getShowValue()
    {
        return $this->showValue;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return DictValue
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return DictValue
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
     * @return DictValue
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
     * @return DictValue
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

<?php

namespace Mirror\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sort
 *
 * @ORM\Table(name="sort", uniqueConstraints={@ORM\UniqueConstraint(name="name", columns={"name", "level"})}, indexes={@ORM\Index(name="parent", columns={"parent_id"}), @ORM\Index(name="left", columns={"left_r"}), @ORM\Index(name="right", columns={"right_r"}), @ORM\Index(name="status", columns={"status"})})
 * @ORM\Entity
 */
class Sort
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="image", type="integer", nullable=true)
     */
    private $image;

    /**
     * @var integer
     *
     * @ORM\Column(name="left_r", type="smallint", nullable=false)
     */
    private $leftR;

    /**
     * @var integer
     *
     * @ORM\Column(name="right_r", type="smallint", nullable=false)
     */
    private $rightR;

    /**
     * @var integer
     *
     * @ORM\Column(name="parent_id", type="integer", nullable=false)
     */
    private $parentId;

    /**
     * @var integer
     *
     * @ORM\Column(name="level", type="smallint", nullable=false)
     */
    private $level = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
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
    private $updateTime = '0000-00-00 00:00:00';



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
     * Set name
     *
     * @param string $name
     *
     * @return Sort
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set image
     *
     * @param integer $image
     *
     * @return Sort
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return integer
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set leftR
     *
     * @param integer $leftR
     *
     * @return Sort
     */
    public function setLeftR($leftR)
    {
        $this->leftR = $leftR;

        return $this;
    }

    /**
     * Get leftR
     *
     * @return integer
     */
    public function getLeftR()
    {
        return $this->leftR;
    }

    /**
     * Set rightR
     *
     * @param integer $rightR
     *
     * @return Sort
     */
    public function setRightR($rightR)
    {
        $this->rightR = $rightR;

        return $this;
    }

    /**
     * Get rightR
     *
     * @return integer
     */
    public function getRightR()
    {
        return $this->rightR;
    }

    /**
     * Set parentId
     *
     * @param integer $parentId
     *
     * @return Sort
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * Get parentId
     *
     * @return integer
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Set level
     *
     * @param integer $level
     *
     * @return Sort
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return integer
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Sort
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
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
     * @return Sort
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
     * @return Sort
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

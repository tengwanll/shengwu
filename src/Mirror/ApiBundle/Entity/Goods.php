<?php

namespace Mirror\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Goods
 *
 * @ORM\Table(name="goods", uniqueConstraints={@ORM\UniqueConstraint(name="goodsNumber", columns={"goods_number", "status"})}, indexes={@ORM\Index(name="sort", columns={"sort_id"}), @ORM\Index(name="price", columns={"price"}), @ORM\Index(name="name", columns={"name"})})
 * @ORM\Entity
 */
class Goods
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
     * @ORM\Column(name="sort_id", type="integer", nullable=false)
     */
    private $sortId;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=11, scale=2, nullable=false)
     */
    private $price = '0.00';

    /**
     * @var integer
     *
     * @ORM\Column(name="image", type="integer", nullable=true)
     */
    private $image = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description = 'NULL';

    /**
     * @var integer
     *
     * @ORM\Column(name="buy_num", type="integer", nullable=false)
     */
    private $buyNum = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="attr", type="blob", length=65535, nullable=true)
     */
    private $attr = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="goods_number", type="string", length=255, nullable=false)
     */
    private $goodsNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="unit", type="string", length=100, nullable=false)
     */
    private $unit;

    /**
     * @var string
     *
     * @ORM\Column(name="standard", type="string", length=100, nullable=false)
     */
    private $standard;

    /**
     * @var string
     *
     * @ORM\Column(name="vender", type="string", length=100, nullable=false)
     */
    private $vender;

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
    private $updateTime = '\'0000-00-00 00:00:00\'';



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
     * @return Goods
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
     * Set sortId
     *
     * @param integer $sortId
     *
     * @return Goods
     */
    public function setSortId($sortId)
    {
        $this->sortId = $sortId;

        return $this;
    }

    /**
     * Get sortId
     *
     * @return integer
     */
    public function getSortId()
    {
        return $this->sortId;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Goods
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set image
     *
     * @param integer $image
     *
     * @return Goods
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
     * Set description
     *
     * @param string $description
     *
     * @return Goods
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
     * Set buyNum
     *
     * @param integer $buyNum
     *
     * @return Goods
     */
    public function setBuyNum($buyNum)
    {
        $this->buyNum = $buyNum;

        return $this;
    }

    /**
     * Get buyNum
     *
     * @return integer
     */
    public function getBuyNum()
    {
        return $this->buyNum;
    }

    /**
     * Set attr
     *
     * @param string $attr
     *
     * @return Goods
     */
    public function setAttr($attr)
    {
        $this->attr = $attr;

        return $this;
    }

    /**
     * Get attr
     *
     * @return string
     */
    public function getAttr()
    {
        return $this->attr;
    }

    /**
     * Set goodsNumber
     *
     * @param string $goodsNumber
     *
     * @return Goods
     */
    public function setGoodsNumber($goodsNumber)
    {
        $this->goodsNumber = $goodsNumber;

        return $this;
    }

    /**
     * Get goodsNumber
     *
     * @return string
     */
    public function getGoodsNumber()
    {
        return $this->goodsNumber;
    }

    /**
     * Set unit
     *
     * @param string $unit
     *
     * @return Goods
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get unit
     *
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Set standard
     *
     * @param string $standard
     *
     * @return Goods
     */
    public function setStandard($standard)
    {
        $this->standard = $standard;

        return $this;
    }

    /**
     * Get standard
     *
     * @return string
     */
    public function getStandard()
    {
        return $this->standard;
    }

    /**
     * Set vender
     *
     * @param string $vender
     *
     * @return Goods
     */
    public function setVender($vender)
    {
        $this->vender = $vender;

        return $this;
    }

    /**
     * Get vender
     *
     * @return string
     */
    public function getVender()
    {
        return $this->vender;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Goods
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
     * @return Goods
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
     * @return Goods
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

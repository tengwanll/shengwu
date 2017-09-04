<?php

namespace Mirror\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Biology
 *
 * @ORM\Table(name="biology")
 * @ORM\Entity
 */
class Biology
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
     * @var string
     *
     * @ORM\Column(name="english_name", type="string", length=255, nullable=true)
     */
    private $englishName;

    /**
     * @var string
     *
     * @ORM\Column(name="sort", type="string", length=255, nullable=true)
     */
    private $sort;

    /**
     * @var string
     *
     * @ORM\Column(name="kind", type="string", length=255, nullable=true)
     */
    private $kind;

    /**
     * @var string
     *
     * @ORM\Column(name="check_gene", type="string", length=255, nullable=true)
     */
    private $checkGene;

    /**
     * @var string
     *
     * @ORM\Column(name="other_gene", type="string", length=255, nullable=true)
     */
    private $otherGene;

    /**
     * @var string
     *
     * @ORM\Column(name="literature", type="string", length=255, nullable=true)
     */
    private $literature;

    /**
     * @var string
     *
     * @ORM\Column(name="disease", type="text", length=65535, nullable=true)
     */
    private $disease;

    /**
     * @var string
     *
     * @ORM\Column(name="keyword", type="string", length=255, nullable=true)
     */
    private $keyword;

    /**
     * @var string
     *
     * @ORM\Column(name="is_usual", type="string", length=10, nullable=true)
     */
    private $isUsual;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", length=65535, nullable=true)
     */
    private $comment;



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
     * @return Biology
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
     * Set englishName
     *
     * @param string $englishName
     *
     * @return Biology
     */
    public function setEnglishName($englishName)
    {
        $this->englishName = $englishName;

        return $this;
    }

    /**
     * Get englishName
     *
     * @return string
     */
    public function getEnglishName()
    {
        return $this->englishName;
    }

    /**
     * Set sort
     *
     * @param string $sort
     *
     * @return Biology
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Get sort
     *
     * @return string
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Set kind
     *
     * @param string $kind
     *
     * @return Biology
     */
    public function setKind($kind)
    {
        $this->kind = $kind;

        return $this;
    }

    /**
     * Get kind
     *
     * @return string
     */
    public function getKind()
    {
        return $this->kind;
    }

    /**
     * Set checkGene
     *
     * @param string $checkGene
     *
     * @return Biology
     */
    public function setCheckGene($checkGene)
    {
        $this->checkGene = $checkGene;

        return $this;
    }

    /**
     * Get checkGene
     *
     * @return string
     */
    public function getCheckGene()
    {
        return $this->checkGene;
    }

    /**
     * Set otherGene
     *
     * @param string $otherGene
     *
     * @return Biology
     */
    public function setOtherGene($otherGene)
    {
        $this->otherGene = $otherGene;

        return $this;
    }

    /**
     * Get otherGene
     *
     * @return string
     */
    public function getOtherGene()
    {
        return $this->otherGene;
    }

    /**
     * Set literature
     *
     * @param string $literature
     *
     * @return Biology
     */
    public function setLiterature($literature)
    {
        $this->literature = $literature;

        return $this;
    }

    /**
     * Get literature
     *
     * @return string
     */
    public function getLiterature()
    {
        return $this->literature;
    }

    /**
     * Set disease
     *
     * @param string $disease
     *
     * @return Biology
     */
    public function setDisease($disease)
    {
        $this->disease = $disease;

        return $this;
    }

    /**
     * Get disease
     *
     * @return string
     */
    public function getDisease()
    {
        return $this->disease;
    }

    /**
     * Set keyword
     *
     * @param string $keyword
     *
     * @return Biology
     */
    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;

        return $this;
    }

    /**
     * Get keyword
     *
     * @return string
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * Set isUsual
     *
     * @param string $isUsual
     *
     * @return Biology
     */
    public function setIsUsual($isUsual)
    {
        $this->isUsual = $isUsual;

        return $this;
    }

    /**
     * Get isUsual
     *
     * @return string
     */
    public function getIsUsual()
    {
        return $this->isUsual;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Biology
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }
}

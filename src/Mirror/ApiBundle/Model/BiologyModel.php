<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/8/28
 * Time: 11:08
 */

namespace Mirror\ApiBundle\Model;

use JMS\DiExtraBundle\Annotation as DI;
use Mirror\ApiBundle\Entity\Biology;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @DI\Service("biology_model",parent="base_model")
 * Class BiologyModel
 * @package Mirror\ApiBundle\Model
 */
class BiologyModel extends BaseModel
{
    private $repositoryName = 'MirrorApiBundle:Biology';

    public function getRepositoryName() {
        return $this->repositoryName;
    }

    /**
     * @param $result
     */
    public function addArray($result){
        $arr=array();
        foreach ($result as $biology){
            $bi=new Biology();
            $date=new \DateTime();
            $bi->setName($biology['name']);
            $bi->setEnglishName($biology['englishName']);
            $bi->setCheckGene($biology['checkGene']);
            $bi->setComment($biology['comment']);
            $bi->setDisease($biology['disease']);
            $bi->setIsUsual($biology['isUsual']);
            $bi->setKeyword($biology['keyword']);
            $bi->setKind($biology['kind']);
            $bi->setLiterature($biology['literature']);
            $bi->setOtherGene($biology['otherGene']);
            $bi->setSort($biology['sort']);
            $bi->setStatus(1);
            $bi->setUpdateTime($date);
            $bi->setCreateTime($date);
            $arr[]=$bi;
        }
        $this->saveArray($arr);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/8/25
 * Time: 11:57
 */

namespace Mirror\ApiBundle\Service;

use JMS\DiExtraBundle\Annotation as DI;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use Mirror\ApiBundle\Common\Code;
use Mirror\ApiBundle\Entity\Biology;
use Mirror\ApiBundle\Model\BiologyModel;
use Mirror\ApiBundle\ViewModel\ReturnResult;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @DI\Service("biology_service")
 * Class BiologyService
 * @package Mirror\ApiBundle\Service
 */
class BiologyService
{
    private $biologyModel;
    private $fileService;
    /**
     * @InjectParams({
     *     "biologyModel"=@Inject("biology_model")
     * })
     * @param BiologyModel $biologyModel
     * @param FileService $fileService
     * BiologyService constructor.
     */
    public function __construct(BiologyModel $biologyModel,FileService $fileService)
    {
        $this->biologyModel=$biologyModel;
        $this->fileService=$fileService;
    }

    /**
     * @param $file
     * @return ReturnResult
     */
    public function BImport($file){
        $rr=new ReturnResult();
        if(pathinfo($file,PATHINFO_EXTENSION )!='xlsx'){
            $rr->errno=Code::$file_not_right_excel;
            return $rr;
        }
        define('PHPEXCEL', dirname(__FILE__) . '/../Util/');
        require(PHPEXCEL . 'Import.php');
        $result=array();
        for ($row = 2;$row <= $highestRow;$row++)
        {
            //注意highestColumnIndex的列数索引从0开始
            $englishName=$objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
            $name=$objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
            if(!$name){
                continue;
            }
            $sort=$objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
            $kind=$objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
            $checkGene=$objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
            $otherGene=$objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
            $disease=$objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
            $keyword=$objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
            $isUsual=$objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
            $comment=$objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
            $result[]=array(
                'name'=>$name,
                'englishName'=>$englishName,
                'sort'=>$sort,
                'kind'=>$kind,
                'checkGene'=>$checkGene,
                'otherGene'=>$otherGene,
                'literature'=>'',
                'disease'=>$disease,
                'keyword'=>$keyword,
                'isUsual'=>$isUsual,
                'comment'=>$comment
            );
        }
        $this->biologyModel->addArray($result);
        return $rr;
    }

    /**
     * @param $name
     * @param $pageable
     * @param $englishName
     * @return ReturnResult
     */
    public function getList($name,$englishName,$pageable){
        $rr=new ReturnResult();
        $arguments=array('status'=>1);
        if($name){
            $arguments['like']=array('name'=>'%'.$name.'%');
        }
        if($englishName){
            $arguments['like']=array('englishName'=>'%'.$englishName.'%');
        }
        $list=$this->biologyModel->getByParams($arguments,$pageable);
        $arr=array();
        foreach($list->getIterator() as $biology){
            /**@var $biology \Mirror\ApiBundle\Entity\Biology*/
            $literatureIds=explode(',',$biology->getLiterature());
            $literatureArr=array();
            foreach ($literatureIds as $literatureId){
                $literatureArr[]=$this->fileService->getUrlAndName($literatureId);

            }
            $arr[]=array(
                'id'=>$biology->getId(),
                'name'=>$biology->getName(),
                'englishName'=>$biology->getEnglishName()?$biology->getEnglishName():'',
                'sort'=>$biology->getSort()?$biology->getSort():'',
                'kind'=>$biology->getKind()?$biology->getKind():'',
                'checkGene'=>$biology->getCheckGene()?$biology->getCheckGene():'',
                'otherGene'=>$biology->getOtherGene()?$biology->getOtherGene():'',
                'literature'=>$literatureArr,
                'disease'=>$biology->getDisease()?$biology->getDisease():'',
            );
        }
        $rr->result=array(
            'list'=>$arr,
            'total'=>$list->count()
        );
        return $rr;
    }

    /**
     * @param $id
     * @return ReturnResult
     */
    public function delete($id){
        $rr=new ReturnResult();
        $biology=$this->biologyModel->getById($id);
        if(!$biology){
            $rr->errno=Code::$biology_not_exist;
            return $rr;
        }
        $this->biologyModel->delete($biology);
        return $rr;
    }

    /**
     * @param $id
     * @return ReturnResult
     */
    public function getInfo($id){
        $rr=new ReturnResult();
        $biology=$this->biologyModel->getById($id);
        if(!$biology){
            $rr->errno=Code::$biology_not_exist;
            return $rr;
        }
        $literatureIds=explode(',',$biology->getLiterature());
        $literatureArr=array();
        foreach ($literatureIds as $literatureId){
            $literatureArr[]=$this->fileService->getUrlAndName($literatureId);

        }
        /**@var $biology \Mirror\ApiBundle\Entity\Biology*/
        $arr=array(
            'id'=>$biology->getId(),
            'name'=>$biology->getName(),
            'englishName'=>$biology->getEnglishName()?$biology->getEnglishName():'',
            'sort'=>$biology->getSort()?$biology->getSort():'',
            'kind'=>$biology->getKind()?$biology->getKind():'',
            'checkGene'=>$biology->getCheckGene()?$biology->getCheckGene():'',
            'otherGene'=>$biology->getOtherGene()?$biology->getOtherGene():'',
            'literature'=>$literatureArr,
            'disease'=>$biology->getDisease()?$biology->getDisease():'',
            'keyword'=>$biology->getKeyword()?$biology->getKeyword():'',
            'isUsual'=>$biology->getIsUsual()?$biology->getIsUsual():'',
            'comment'=>$biology->getComment()?$biology->getComment():''
        );
        $rr->result=$arr;
        return $rr;
    }

    /**
     * @param $id
     * @param Biology $biology
     * @return ReturnResult
     */
    public function update($id,Biology $biology){
        $rr=new ReturnResult();
        $biologyData=$this->biologyModel->getById($id);
        /**@var $biologyData \Mirror\ApiBundle\Entity\Biology*/
        $biologyData->setName($biology->getName());
        $biologyData->setEnglishName($biology->getEnglishName());
        $biologyData->setSort($biology->getSort());
        $biologyData->setKind($biology->getKind());
        $biologyData->setCheckGene($biology->getCheckGene());
        $biologyData->setLiterature(implode(',',$biology->getLiterature()));
        $biologyData->setDisease($biology->getDisease());
        $biologyData->setIsUsual($biology->getIsUsual());
        $biologyData->setComment($biology->getComment());
        $biologyData->setOtherGene($biology->getOtherGene());
        $biologyData->setKeyword($biology->getKeyword());
        $this->biologyModel->save($biologyData);
        return $rr;
    }

    /**
     * @param Biology $biology
     * @return ReturnResult
     */
    public function create(Biology $biology){
        $rr=new ReturnResult();
        $date=new \DateTime();
        $biology->setStatus(1);
        $biology->setCreateTime($date);
        $biology->setUpdateTime($date);
        $this->biologyModel->save($biology);
        return $rr;
    }
}
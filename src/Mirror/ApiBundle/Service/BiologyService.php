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
use Mirror\ApiBundle\Model\BiologyModel;
use Mirror\ApiBundle\ViewModel\ReturnResult;

/**
 * @DI\Service("biology_service")
 * Class BiologyService
 * @package Mirror\ApiBundle\Service
 */
class BiologyService
{
    private $biologyModel;
    /**
     * @InjectParams({
     *     "biologyModel"=@Inject("biology_model")
     * })
     * @param BiologyModel $biologyModel
     * BiologyService constructor.
     */
    public function __construct(BiologyModel $biologyModel)
    {
        $this->biologyModel=$biologyModel;
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
            $literature=$objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
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
                'literature'=>$literature,
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
     * @return ReturnResult
     */
    public function getList($name,$pageable){
        $rr=new ReturnResult();
        $arguments=array('status'=>1);
        if($name){
            $arguments['like']=array('name'=>'%'.$name.'%');
        }
        $list=$this->biologyModel->getByParams($arguments,$pageable,'createTime desc');
        $arr=array();
        foreach($list->getIterator() as $biology){
            /**@var $biology \Mirror\ApiBundle\Entity\Biology*/
            $arr[]=array(
                'id'=>$biology->getId(),
                'name'=>$biology->getName(),
                'englishName'=>$biology->getEnglishName(),
                'sort'=>$biology->getSort(),
                'kind'=>$biology->getKind(),
                'checkGene'=>$biology->getCheckGene(),
                'otherGene'=>$biology->getOtherGene(),
                'literature'=>$biology->getLiterature(),
                'disease'=>$biology->getDisease()
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
}
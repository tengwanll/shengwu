<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/12/15
 * Time: 16:06
 */

namespace Mirror\ApiBundle\Service;


use JMS\DiExtraBundle\Annotation as DI;
use Mirror\ApiBundle\Model\HpvModel;
use Mirror\ApiBundle\ViewModel\ReturnResult;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;

/**
 * @DI\Service("hpv_service")
 * Class HpvService
 * @package Mirror\ApiBundle\Service
 */
class HpvService
{
    private $hpvModel;
    private $fileService;

    /**
     * @InjectParams({
     *
     * })
     * HpvService constructor.
     * @param HpvModel $hpvModel
     */
    public function __construct(HpvModel $hpvModel,FileService $fileService)
    {
        $this->hpvModel=$hpvModel;
        $this->fileService=$fileService;
    }
    
    public function getList($orderNo,$conn,$pageable){
        $rr=new ReturnResult();
        $lists=$this->hpvModel->getList($orderNo,$conn,$pageable);
        $total=$this->hpvModel->getCount($orderNo,$conn);
        $rr->result=array(
            'list'=>$lists,
            'total'=>$total[0]['total']
        );
        return $rr;
    }

    public function getInfo($id,$conn){
        $rr=new ReturnResult();
        $lists=$this->hpvModel->getInfo($id,$conn);
        if(isset($lists[0])&&$lists[0]){
            $reportId=$lists[0]['report'];
            $lists[0]['report']=$this->fileService->getFullUrlById($reportId);
        }
        $rr->result=$lists[0];
        return $rr;
    }
}
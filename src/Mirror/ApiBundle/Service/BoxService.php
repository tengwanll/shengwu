<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/12/15
 * Time: 16:06
 */

namespace Mirror\ApiBundle\Service;


use JMS\DiExtraBundle\Annotation as DI;
use Mirror\ApiBundle\Common\Code;
use Mirror\ApiBundle\Model\BoxModel;
use Mirror\ApiBundle\Util\Helper;
use Mirror\ApiBundle\ViewModel\ReturnResult;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;

/**
 * @DI\Service("box_service")
 * Class HpvService
 * @package Mirror\ApiBundle\Service
 */
class BoxService
{
    private $boxModel;
    private $fileService;

    /**
     * @InjectParams({
     *
     * })
     * HpvService constructor.
     * @param BoxModel $hpvModel
     */
    public function __construct(BoxModel $boxModel,FileService $fileService)
    {
        $this->boxModel=$boxModel;
        $this->fileService=$fileService;
    }
    
    public function getList($uniqueId,$conn,$pageable){
        $rr=new ReturnResult();
        $lists=$this->boxModel->getList($uniqueId,$conn,$pageable);
        $arr=array();
        foreach($lists as $box){
            $status=$box['status'];
            $reportId=$box['report'];
            $report=$this->fileService->getFullUrlById($reportId);
            if($status==1){
                $status='盒子初始生成';
            }elseif($status==2){
                $status='用户已经填写';
            }elseif($status==3){
                $status='检测结果已出';
            }else{
                $status='报表已经上传';
            }
            $arr[]=array(
                'id'=>$box['id'],
                'uniqueId'=>$box['unique_id'],
                'codeUrl'=>$box['code_url'],
                'status'=>$status,
                'report'=>$report,
                'createTime'=>$box['create_time']
            );
        }
        $total=$this->boxModel->getCount($uniqueId,$conn);
        $rr->result=array(
            'list'=>$arr,
            'total'=>$total[0]['total']
        );
        return $rr;
    }

    public function add($num,$conn){
        $rr=new ReturnResult();
        $string='';
        $date=date('Y-m-d H:i:s');
        $start=(int)Helper::getNumByFile();
        for($i=0;$i<$num;$i++){
            $start++;
            $uniqueId=sprintf('%09s', $start);
            $url=Helper::createQrCode('http://weixin.amogene.com/web/face/'.base64_encode($uniqueId),$uniqueId);
            if($i){
                $string.=",('$uniqueId','$url','',1,'$date','$date')";
            }else{
                $string.="('$uniqueId','$url','',1,'$date','$date')";
            }
        }
        Helper::setNumByFile($start);
        $sql='insert into weixin.box(unique_id,code_url,report,status,update_time,create_time) value'.$string;
        $conn->beginTransaction();
        $res=$conn->exec($sql);
        if($res){
            $conn->commit();
        }else{
            $conn->rollback();
            $rr->errno=Code::$box_add_fail;
        }
        return $rr;
    }

    public function getInfo($id,$conn){
        $rr=new ReturnResult();
        $box=$this->boxModel->getBoxDetail($id,$conn);
        $arr=array();
        if(isset($box[0])&&$box[0]){
            $status=$box[0]['status'];
            if($status==1){
                $status='盒子初始生成';
            }elseif($status==2){
                $status='用户已经填写';
            }elseif($status==3){
                $status='检测结果已出';
            }else{
                $status='报表已经上传';
            }
            $arr=array(
                'uniqueId'=>$box[0]['unique_id'],
                'codeUrl'=>$box[0]['code_url'],
                'status'=>$status,
                'createTime'=>$box[0]['create_time'],
                'boxInfo'=>array(),
                'boxGene'=>array()
            );
            $boxInfo=$this->boxModel->getBoxInfoDetail($box['unique_id'],$conn);
            if(isset($boxInfo[0])&&$boxInfo[0]){
                $arr['boxInfo']=array(
                    'name'=>$boxInfo[0]['name'],
                    'age'=>$boxInfo[0]['age'],
                    'gender'=>$boxInfo[0]['gender'],
                    'email'=>$boxInfo[0]['email'],
                    'telephone'=>$boxInfo[0]['telephone'],
                    'ability'=>json_decode($boxInfo[0]['ability'])
                );
            }
            $boxGene=$this->boxModel->getBoxGeneDetail($box['unique_id'],$conn);
            if(isset($boxGene[0])&&$boxGene[0]){
                $arr['boxGene']=$boxGene[0];
            }
        }
        $rr->result=$arr;
        return $rr;
    }
}
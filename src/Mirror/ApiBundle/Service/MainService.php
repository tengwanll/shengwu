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
use Mirror\ApiBundle\Common\Constant;
use Mirror\ApiBundle\Model\BannerModel;
use Mirror\ApiBundle\Model\BoxModel;
use Mirror\ApiBundle\Model\CompanyModel;
use Mirror\ApiBundle\Model\WeatherModel;
use Mirror\ApiBundle\Util\Helper;
use Mirror\ApiBundle\Util\JsonHelper;
use Mirror\ApiBundle\Util\JsonParser;
use Mirror\ApiBundle\Util\WeatherHelper;
use Mirror\ApiBundle\ViewModel\ReturnResult;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;

/**
 * @DI\Service("main_service")
 * Class HpvService
 * @package Mirror\ApiBundle\Service
 */
class MainService
{
    private $companyModel;
    private $weatherModel;
    private $bannerModel;
    private $fileService;

    /**
     * @InjectParams({
     *
     * })
     * HpvService constructor.
     * @param BoxModel $hpvModel
     */
    public function __construct(CompanyModel $companyModel,FileService $fileService,WeatherModel $weatherModel,BannerModel $bannerModel)
    {
        $this->companyModel=$companyModel;
        $this->fileService=$fileService;
        $this->weatherModel=$weatherModel;
        $this->bannerModel=$bannerModel;
    }
    
    public function getCompanyList($redis){
        $rr=new ReturnResult();
        $companys=$this->companyModel->getOneByProperty('status',Constant::$status_normal);
        $arr=array();
        foreach($companys as $company){
            $address=$company['address'];
//            $weather=$redis->get($address);
//            if($weather){
//                $weather=json_decode($weather,true);
//            }else{
                $weather=WeatherHelper::getResult($address);
//                $redis->set($address,json_encode($weather));
//                $redis->expireAt($address,strtotime(date('Y-m-d').' 23:59:59'));
//            }
            $wid=$weather['result']['today']['weather'];
            $logo=$this->weatherModel->getOneByProperty('wid',$wid);
            $logo=$this->fileService->getFullUrlById($logo['logo']);
            $banners=$this->bannerModel->getByPages(array('type'=>Constant::$banner_type_company,'contact_id'=>$company['id']));
            $bannerArr=array();
            foreach($banners as $banner){
                $photo=$this->fileService->getFullUrlById($banner['photo']);
                $bannerArr[]=$photo;
            }
            $arr[]=array(
                'name'=>$company['name'],
                'detail'=>$company['detail'],
                'phone'=>$company['phone'],
                'address'=>$address,
                'temperature'=>$weather['result']['today']['temperature'],
                'weather'=>$weather['result']['today']['weather'],
                'logo'=>$logo,
                'banner'=>$bannerArr
            );
        }
        $rr->result=array(
            'list'=>$arr
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
            $address='http://weixin.amogene.com/web/face/'.base64_encode($uniqueId);
            if($i){
                $string.=",('$uniqueId','$url','',1,'$date','$date','$address')";
            }else{
                $string.="('$uniqueId','$url','',1,'$date','$date','$address')";
            }
        }
        Helper::setNumByFile($start);
        $sql='insert into weixin.box(unique_id,code_url,report,status,update_time,create_time,address) value'.$string;
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
            $boxInfo=$this->boxModel->getBoxInfoDetail($box[0]['unique_id'],$conn);
            if(isset($boxInfo[0])&&$boxInfo[0]){
                if($boxInfo[0]['gender']){
                    $gender='男性';
                }else{
                    $gender='女性';
                }
                $ability=json_decode($boxInfo[0]['ability'],true);
                $abilityStr=array();
                if($ability){
                    $abilities=Constant::$abilities;
                    foreach ($ability as $item){
                        $abilityStr[]=$abilities[$item];
                    }
                }
                $arr['boxInfo']=array(
                    'name'=>$boxInfo[0]['name'],
                    'age'=>$boxInfo[0]['age'],
                    'gender'=>$gender,
                    'email'=>$boxInfo[0]['email'],
                    'telephone'=>$boxInfo[0]['telephone'],
                    'ability'=>implode(',',$abilityStr)
                );
            }
            $boxGene=$this->boxModel->getBoxGeneDetail($box[0]['unique_id'],$conn);
            if(isset($boxGene[0])&&$boxGene[0]){
                $arr['boxGene']=$boxGene[0];
            }
        }
        $rr->result=$arr;
        return $rr;
    }

    public function getBoxGene($unique_id,$conn){
        $rr=new ReturnResult();
        $boxGene=$this->boxModel->getBoxGeneDetail($unique_id,$conn);
        $arr=array();
        if(isset($boxGene[0])&&$boxGene[0]){
            $arr=$boxGene[0];
        }
        $rr->result=array(
            'boxGene'=>$arr
        );
        return $rr;
    }

    /**
     * @param JsonParser $json
     * @param $conn
     * @return ReturnResult
     */
    public function updateBoxGene(JsonParser $json,$conn){
        $rr=new ReturnResult();
        $boxId=$json->get('boxId','');
        $IL6=$json->get('IL6','');
        $HLA_C=$json->get('HLA_C','');
        $ZNF365=$json->get('ZNF365','');
        $MMP1=$json->get('MMP1','');
        $AQP3=$json->get('AQP3','');
        $NQO1=$json->get('NQO1','');
        $SOD2=$json->get('SOD2','');
        $NFE2L2=$json->get('NFE2L2','');
        $CAT=$json->get('CAT','');
        $MC1R=$json->get('MC1R','');
        $GSTP1=$json->get('GSTP1','');
        $IRF4=$json->get('IRF4','');
        $boxGene=$this->boxModel->getBoxGeneDetail($boxId,$conn);

        if(isset($boxGene[0])&&$boxGene[0]){
            $sql="update weixin.box_gene set IL6='$IL6',HLA_C='$HLA_C',ZNF365='$ZNF365',MMP1='$MMP1',AQP3='$AQP3',NQO1='$NQO1',SOD2='$SOD2',NFE2L2='$NFE2L2',CAT='$CAT',MC1R='$MC1R',GSTP1='$GSTP1',IRF4='$IRF4' where box_id=$boxId ";
        }else{
            $date=date('Y-m-d H:i:s');
            $sql="insert into weixin.box_gene(box_id,IL6,HLA_C,ZNF365,MMP1,AQP3,NQO1,SOD2,NFE2L2,CAT,MC1R,GSTP1,IRF4,status,create_time,update_time) value($boxId,'$IL6','$HLA_C','$ZNF365','$MMP1','$AQP3','$NQO1','$SOD2','$NFE2L2','$CAT','$MC1R','$GSTP1','$IRF4',1,'$date','$date')";
        }
        $conn->exec($sql);
        $sql='update weixin.box set status=3 where unique_id='.$boxId;
        $conn->exec($sql);
        return $rr;
    }
}
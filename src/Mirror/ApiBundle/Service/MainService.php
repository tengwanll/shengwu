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
use Mirror\ApiBundle\Model\JoinModel;
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
    private $joinModel;

    /**
     * @InjectParams({
     *
     * })
     * HpvService constructor.
     * @param BoxModel $hpvModel
     */
    public function __construct(CompanyModel $companyModel,FileService $fileService,WeatherModel $weatherModel,BannerModel $bannerModel,JoinModel $joinModel)
    {
        $this->companyModel=$companyModel;
        $this->fileService=$fileService;
        $this->weatherModel=$weatherModel;
        $this->bannerModel=$bannerModel;
        $this->joinModel=$joinModel;
    }

    /**
     * 获取公司列表
     * @param $redis
     * @return ReturnResult
     */
    public function getCompanyList($redis){
        $rr=new ReturnResult();
        $companys=$this->companyModel->getByProperty('status',Constant::$status_normal);
        $arr=array();
        foreach($companys as $company){
            $address=$company['address'];
            $weather=$redis->get($address);
            if($weather){
                $weather=json_decode($weather,true);
            }else{
                $weather=WeatherHelper::getResult($address);
                $redis->set($address,json_encode($weather));
                $redis->expireAt($address,strtotime(date('Y-m-d').' 23:59:59'));
            }
            $wid=$weather['result']['today']['weather_id']['fa'];

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
                'banner'=>$bannerArr,
                'date'=>date('Y-m-d'),
                'nameAs'=>$company['name_as']
            );
        }
        $rr->result=array(
            'list'=>$arr
        );
        return $rr;
    }

    /**
     * @return ReturnResult
     */
    public function getJoinList(){
        $rr=new ReturnResult();
        $joinList=$this->joinModel->getAll();
        $rr->result=array('list'=>$joinList);
        return $rr;
    }

    /**
     * @param $id
     * @return ReturnResult
     */
    public function getJoinInfo($id){
        $rr=new ReturnResult();
        $join=$this->joinModel->getById($id);
        $rr->result=array('info'=>$join);
        return $rr;
    }
}
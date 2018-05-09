<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/6/1
 * Time: 14:10
 */

namespace Mirror\ApiBundle\Service;

use JMS\DiExtraBundle\Annotation as DI;
use Mirror\ApiBundle\Common\Code;
use Mirror\ApiBundle\Common\Constant;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use Mirror\ApiBundle\Model\CompanyModel;
use Mirror\ApiBundle\Model\ServerModel;
use Mirror\ApiBundle\Model\ServerSortModel;
use Mirror\ApiBundle\Model\SystemSettingModel;
use Mirror\ApiBundle\ViewModel\ReturnResult;

/**
 * @DI\Service("server_service")
 * Class CarService
 * @package Mirror\ApiBundle\Service
 */
class ServerService
{
    private $serverModel;
    private $fileService;
    private $serverSortModel;
    private $systemSettingModel;
    private $companyModel;

    /**
     * @InjectParams({
     *
     * })
     * CarService constructor.
     * @param ServerModel $serverModel
     * @param ServerSortModel $serverSortModel
     * @param FileService $fileService
     * @param SystemSettingModel $systemSettingModel
     */
    public function __construct(ServerModel $serverModel,ServerSortModel $serverSortModel,FileService $fileService,SystemSettingModel $systemSettingModel,CompanyModel $companyModel)
    {
        $this->serverModel=$serverModel;
        $this->serverSortModel=$serverSortModel;
        $this->fileService=$fileService;
        $this->systemSettingModel=$systemSettingModel;
        $this->companyModel=$companyModel;
    }

    /**
     * 获取首页菜单显示的服务
     * @param $pageable
     * @param $userId
     * @return ReturnResult
     */
    public function getIndexServer(){
        $rr=new ReturnResult();
        $sorts=$this->serverSortModel->getByProperty('level',1);
        $company=$this->companyModel->getOneByProperty('name_as',Constant::$main_company);
        if($company)
            $company['logo']=$this->fileService->getFullUrlById($company['logo']);
        $arr=array();
        foreach ($sorts as $sort){
            $left_r=$sort['left_r'];
            $right_r=$sort['right_r'];
            $params=array(
                'status'=>Constant::$status_normal
            );
            $data=$this->serverModel->getList($params,'','',$left_r,$right_r);
            foreach($data as $key=>$val){
                $data[$key]['logo']=$this->fileService->getFullUrlById($val['logo']);
                $data[$key]['banner']=$this->fileService->getFullUrlById($val['banner']);
            }
            $arr[]=array(
                'name'=>$sort['name'],
                'sortId'=>$sort['id'],
                'server'=>$data
            );
        }
        $rr->result=array('list'=>$arr,'company'=>$company);
        return $rr;
    }

    /**
     * 获取中间页分类的服务
     * @param $sortId  //分类id
     * @return ReturnResult
     */
    public function getListBySort($sortId){
        $rr=new ReturnResult();
        $arr=array();
        $nowSort=$this->serverSortModel->getById($sortId);
        $sorts=$this->serverSortModel->getByProperty('parent_id',$sortId);
        foreach($sorts as $sort){
            $left_r=$sort['left_r'];
            $right_r=$sort['right_r'];
            $sort['logo']=$this->fileService->getFullUrlById($sort['logo']);
            $noUp=array(
                'status'=>Constant::$status_normal,
                'is_up'=>0
            );
            $list=$this->serverModel->getList($noUp,'','',$left_r,$right_r);
            foreach($list as $key=>$val){
                $list[$key]['logo']=$this->fileService->getFullUrlById($val['logo']);
                $list[$key]['banner']=$this->fileService->getFullUrlById($val['banner']);
            }
            $up=array(
                'status'=>Constant::$status_normal,
                'is_up'=>1
            );
            $upList=$this->serverModel->getList($up,'','',$left_r,$right_r);
            foreach($upList as $key=>$val){
                $upList[$key]['logo']=$this->fileService->getFullUrlById($val['logo']);
                $upList[$key]['banner']=$this->fileService->getFullUrlById($val['banner']);
            }
            $arr[]=array(
                'sort'=>$sort,
                'lists'=>$list,
                'up_list'=>$upList
            );
        }
        $rr->result=array('data'=>$arr,'name'=>$nowSort['name']);
        return $rr;
    }

    /**
     * 获取服务详情
     * @param $id
     * @return ReturnResult
     */
    public function detail($id){
        $rr=new ReturnResult();
        $server=$this->serverModel->getById($id);
        $server['banner']=$this->fileService->getFullUrlById($server['banner']);
        $rr->result=$server;
        return $rr;
    }

    public function add($data){
        $rr=new ReturnResult();
        $data['status']=Constant::$status_normal;
        $data['create_time']=date('Y-m-d H:i:s');
        $data['update_time']=date('Y-m-d H:i:s');
        $server=$this->serverModel->save($data);
        if(!$server){
            $rr->errno=Code::$add_error;
            return $rr;
        }
        $rr->result=array('id'=>$server);
        return $rr;
    }

}

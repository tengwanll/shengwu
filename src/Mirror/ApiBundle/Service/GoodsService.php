<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/5/15
 * Time: 17:22
 */

namespace Mirror\ApiBundle\Service;


use JMS\DiExtraBundle\Annotation as DI;
use Mirror\ApiBundle\Common\Code;
use Mirror\ApiBundle\Common\Constant;
use Mirror\ApiBundle\Entity\Goods;
use Mirror\ApiBundle\Model\CarModel;
use Mirror\ApiBundle\Model\GoodsModel;
use Mirror\ApiBundle\Model\SortAttrModel;
use Mirror\ApiBundle\Model\SortModel;
use Mirror\ApiBundle\Model\SystemSettingModel;
use Mirror\ApiBundle\ViewModel\ReturnResult;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;

/**
 * @DI\Service("goods_service")
 * Class GoodsService
 * @package Mirror\ApiBundle\Service
 */
class GoodsService
{
    private $sortModel;
    private $goodsModel;
    private $fileService;
    private $systemSettingModel;
    private $sortAttrModel;
    private $carModel;

    /**
     * @InjectParams({
     *     "sortModel"=@Inject("sort_model"),
     *     "goodsModel"=@Inject("goods_model"),
     *     "fileService"=@Inject("file_service"),
     *     "systemSettingModel"=@Inject("system_setting_model"),
     *     "sortAttrModel"=@Inject("sort_attr_model"),
     *     "carModel"=@Inject("car_model")
     * })
     * GoodsService constructor.
     * @param SortModel $sortModel
     * @param GoodsModel $goodsModel
     * @param FileService $fileService
     * @param SystemSettingModel $systemSettingModel
     */
    public function __construct(SortModel $sortModel,GoodsModel $goodsModel,FileService $fileService,SystemSettingModel $systemSettingModel,SortAttrModel $sortAttrModel,CarModel $carModel)
    {
        $this->goodsModel=$goodsModel;
        $this->sortModel=$sortModel;
        $this->fileService=$fileService;
        $this->systemSettingModel=$systemSettingModel;
        $this->sortAttrModel=$sortAttrModel;
        $this->carModel=$carModel;
    }

    /**
     * @param $name
     * @param $sortName
     * @param $bigPrice
     * @param $smallPrice
     * @param $pageable
     * @return ReturnResult
     */
    public function getList($name,$sortName,$bigPrice,$smallPrice,$pageable,$attr,$conn){
        $rr=new ReturnResult();
        $arguments=array();
        $left=0;
        $right=0;
        if($sortName){
            $sort=$this->sortModel->getOneByProperty('name',$sortName);
            if(!$sort){
                $rr->errno=Code::$sort_not_exist;
                return $rr;
            }
            $left=$sort->getLeftR();
            $right=$sort->getRightR();
        }
        if($bigPrice){
            $arguments['<']=array('price'=>$bigPrice);
        }
        if($smallPrice){
            $arguments['>']=array('price'=>$smallPrice);
        }
        $list=$this->goodsModel->getList($arguments,$pageable,'create_time desc',$left,$right,$attr,$name,$conn);
        $count=$this->goodsModel->getCount($arguments,$left,$right,$attr,$name,$conn);
        $arr=array();
        foreach($list as $goods){
            $imageId=$goods['image'];
            $image=$this->fileService->getFullUrlById($imageId);
            $arr[]=array(
                'id'=>$goods['id'],
                'name'=>$goods['name'],
                'sort'=>$goods['sortName'],
                'price'=>$goods['price'],
                'image'=>$image,
                'status'=>$goods['status'],
                'goodsNumber'=>$goods['goods_number'],
                'unit'=>$goods['unit'],
                'standard'=>$goods['standard'],
                'vender'=>$goods['vender'],
                'attr'=>isset($goods['myAttr'])?$goods['myAttr']:''
            );
        }
        $rr->result=array(
            'list'=>$arr,
            'total'=>$count[0]['total']
        );
        return $rr;
    }

    /**
     * @param $id
     * @return ReturnResult
     */
    public function getDetail($id,$conn){
        $rr=new ReturnResult();
        $goods=$this->goodsModel->getDetail($id,$conn);
        /**@var $goods \Mirror\ApiBundle\Entity\Goods*/
        if(!$goods){
            $rr->errno=Code::$goods_not_exist;
            return $rr;
        }
        $sortId=$goods['sort_id'];
        $sort=$this->sortModel->getById($sortId);
        $imageId=$goods['image'];
        $image=$this->fileService->getFullUrlById($imageId);
        $arr=array(
            'name'=>$goods['name'],
            'sort'=>$sort?$sort->getName():'',
            'price'=>$goods['price'],
            'image'=>$image,
            'description'=>$goods['description'],
            'buyNum'=>$goods['buy_num'],
            'goodsNumber'=>$goods['goods_number'],
            'unit'=>$goods['unit'],
            'standard'=>$goods['standard'],
            'vender'=>$goods['vender'],
            'attr'=>json_decode($goods['attrs']),
            'createTime'=>$goods['create_time'],
            'status'=>$goods['status']
        );
        $rr->result=$arr;
        return $rr;
    }

    /**
     * @param $goodsId
     * @param $userId
     * @return ReturnResult
     */
    public function addToCar($goodsId,$userId){
        $rr=new ReturnResult();
        $goods=$this->goodsModel->getById($goodsId);
        if(!$goods){
            $rr->errno=Code::$goods_not_exist;
            return $rr;
        }
        $price=$goods->getPrice();
        $carGoods=$this->carModel->getOneByCriteria(array('goodsId'=>$goodsId,'userId'=>$userId,'status'=>1));
        if($carGoods){
            $carGoods->setNumber($carGoods->getNumber()+1);
            $carGoods->setPrice($carGoods->getPrice()+$price);
            $this->carModel->save($carGoods);
        }else{
            $this->carModel->add($userId,$goodsId,1,$price);
        }
        return $rr;
    }

    /**
     * @param Goods $goods
     * @param $conn
     * @return ReturnResult
     */
    public function create(Goods $goods,$conn){
        $rr=new ReturnResult();
        $data=$this->goodsModel->getOneByCriteria(array('name'=>$goods->getName(),'status'=>Constant::$status_normal));
        if($data){
            $rr->errno=Code::$goods_had_exist;
            return $rr;
        }
        $this->goodsModel->add($goods->getName(),$goods->getSortId(),$goods->getPrice(),$goods->getDescription(),$goods->getAttr(),$conn,$goods->getImage(),$goods->getGoodsNumber(),$goods->getUnit(),$goods->getStandard(),$goods->getVender());
        return $rr;
    }

    /**
     * @param $goodsId
     * @param $status
     * @return ReturnResult
     */
    public function changeStatus($goodsId,$status){
        $rr=new ReturnResult();
        $goods=$this->goodsModel->getById($goodsId);
        if(!$goods){
            $rr->errno=Code::$goods_not_exist;
            return $rr;
        }
        if($status){
            $exist=$this->goodsModel->getOneByCriteria(array('name'=>$goods->getName(),'status'=>Constant::$status_normal));
            if($exist){
                $rr->errno=Code::$goods_had_on;
                return $rr;
            }
        }
        if($status!==null){
            $goods->setStatus($status);
            $this->goodsModel->save($goods);
        }
        return $rr;
    }

    public function update(Goods $goods,$conn,$id){
        $rr=new ReturnResult();
        $this->goodsModel->update($id,$goods->getName(),$goods->getSortId(),$goods->getPrice(),$goods->getDescription(),$goods->getAttr(),$conn,$goods->getImage(),$goods->getGoodsNumber(),$goods->getUnit(),$goods->getStandard(),$goods->getVender());
        return $rr;
    }

}
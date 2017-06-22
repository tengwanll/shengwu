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
    public function getList($name,$sortName,$bigPrice,$smallPrice,$pageable){
        $rr=new ReturnResult();
        $arguments=array();
        if($name){
            $arguments['like']=array('name'=>'%'.$name.'%');
        }
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
        $list=$this->goodsModel->getList($arguments,$pageable,'createTime',$left,$right);
        $arr=array();
        foreach($list->getIterator() as $goods){
            /**@var $goods \Mirror\ApiBundle\Entity\Goods*/
            $sortId=$goods->getSortId();
            $sort=$this->sortModel->getById($sortId);
            $imageId=$goods->getImage();
            $image=$this->fileService->getFullUrlById($imageId);
            $arr[]=array(
                'id'=>$goods->getId(),
                'name'=>$goods->getName(),
                'sort'=>$sort?$sort->getName():'',
                'price'=>$goods->getPrice(),
                'image'=>$image,
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
        $this->goodsModel->add($goods->getName(),$goods->getSortId(),$goods->getPrice(),$goods->getDescription(),$goods->getAttr(),$conn,$goods->getImage());
        return $rr;
    }

}
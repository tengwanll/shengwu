<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/5/27
 * Time: 10:50
 */

namespace Mirror\ApiBundle\Service;


use JMS\DiExtraBundle\Annotation as DI;
use Mirror\ApiBundle\Common\Code;
use Mirror\ApiBundle\Common\Constant;
use Mirror\ApiBundle\Model\GoodsModel;
use Mirror\ApiBundle\Model\SortAttrModel;
use Mirror\ApiBundle\Model\SortModel;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use Mirror\ApiBundle\ViewModel\ReturnResult;

/**
 * @DI\Service("sort_service")
 * Class SortService
 * @package Mirror\ApiBundle\Service
 */
class SortService
{
    private $sortModel;
    private $sortAttrModel;
    private $fileService;
    private $goodsModel;

    /**
     * @InjectParams({
     *     "sortModel"=@Inject("sort_model"),
     *     "sortAttrModel"=@Inject("sort_attr_model"),
     *     "fileService"=@Inject("file_service"),
     *     "systemSettingModel"=@Inject("system_setting_model"),
     *     "sortAttrModel"=@Inject("sort_attr_model"),
     *     "goodsModel"=@Inject("goods_model")
     * })
     * SortService constructor.
     * @param SortModel $sortModel
     * @param SortAttrModel $sortAttrModel
     * @param FileService $fileService
     */
    public function __construct(SortModel $sortModel,SortAttrModel $sortAttrModel,FileService $fileService,GoodsModel $goodsModel)
    {
        $this->sortModel=$sortModel;
        $this->sortAttrModel=$sortAttrModel;
        $this->fileService=$fileService;
        $this->goodsModel=$goodsModel;
    }

    /**
     * 获取分类分组
     * @return ReturnResult
     */
    public function getList(){
        $rr=new ReturnResult();
        $arguments=array('status'=>1);
        $data=$this->sortModel->getByPages($arguments);
        $arr=$this->orderSort($data);
        $rr->result=array('list'=>$arr);
        return $rr;
    }

    /**
     * 递归给分类分组
     * @param $array
     * @param int $parentId
     * @return array
     */
    private function orderSort ($array,$parentId=0){
        $arr = array();
        foreach($array as $key=>$sort){
            /**@var $sort \Mirror\ApiBundle\Entity\Sort*/
            unset($array[$key]);
            $imageId=$sort->getImage();
            $image=$this->fileService->getFullUrlById($imageId);
            if($sort->getParentId()==$parentId){

                $child=array();
                if($sort->getRightR()-1>$sort->getLeftR()){
                    $child=$this->orderSort($array,$sort->getId());
                }
                $arr[] = array(
                    'id'=>$sort->getId(),
                    'name'=>$sort->getName(),
                    'image'=>$image,
                    'level'=>$sort->getLevel(),
                    'child'=>$child
                );
            }
        }
        return $arr;
    }

    /**
     * @param $name
     * @param $parentId
     * @param $image
     * @param $attr
     * @return ReturnResult
     */
    public function add($name,$parentId,$image,$attr){
        $rr=new ReturnResult();
        $parentSort=$this->sortModel->getById($parentId);
        /**@var $parentSort \Mirror\ApiBundle\Entity\Sort*/
        if(!$parentSort){
            $rr->errno=Code::$sort_not_exist;
            return $rr;
        }
        $right=$parentSort->getRightR();
        $level=$parentSort->getLevel();
        try {
            $this->sortModel->getEntityManager()->beginTransaction();
            $this->sortModel->moveSortLeft($right);
            $this->sortModel->moveSortRight($right);
            $sort=$this->sortModel->add($right,$level,$parentId,$name,$image);
            if($attr){
                $this->sortAttrModel->add($attr,$sort->getId());
            }
            $this->sortModel->getEntityManager()->commit();
        } catch (\Exception $e) {
            $this->sortModel->getEntityManager()->rollback();
            $rr->errno = Code::$sort_add_fail;
            return $rr;
        }
        $rr->result=array('sort'=>$sort->getId());
        return $rr;
    }

    public function detail($id){
        $rr=new ReturnResult();
        $sort=$this->sortModel->getById($id);
        if(!$sort){
            $rr->errno=Code::$sort_not_exist;
            return $rr;
        }
        /**@var $sort \Mirror\ApiBundle\Entity\Sort*/
        $parentId=$sort->getParentId();
        $parentSort=$this->sortModel->getById($parentId);
        $imageId=$sort->getImage();
        $image=$this->fileService->getFullUrlById($imageId);
        $id=$sort->getId();
        $attr=$this->sortAttrModel->getByProperty('sortId',$id);
        $attrs=array();
        foreach ($attr as $sortAttr){
            /**@var $sortAttr \Mirror\ApiBundle\Entity\SortAttr*/
            $attrs[]=$sortAttr->getName();
        }
        $arr=array(
            'id'=>$id,
            'parentName'=>$parentSort?$parentSort->getName():'顶级',
            'name'=>$sort->getName(),
            'level'=>$sort->getLevel(),
            'image'=>$image,
            'attr'=>implode(',',$attrs)
        );
        $rr->result=$arr;
        return $rr;
    }

    /**
     * @param $name
     * @param $id
     * @param $image
     * @param $attr
     * @return ReturnResult
     */
    public function edit($name,$id,$image,$attr){
        $rr=new ReturnResult();
        $sort=$this->sortModel->getById($id);
        if(!$sort){
            $rr->errno=Code::$sort_not_exist;
            return $rr;
        }
        /**@var $sort \Mirror\ApiBundle\Entity\Sort*/
        if($name){
            $sort->setName($name);
        }
        if($image){
            $sort->setImage($image);
        }
        $this->sortModel->save($sort);
        $sortAttr=$this->sortAttrModel->getByProperty('sortId',$id);
        foreach($sortAttr as $attribute){
            $this->sortAttrModel->delete($attribute);
        }
        if($attr){
            $this->sortAttrModel->add($attr,$id);
        }
        return $rr;
    }

    public function getLeaf(){
        $rr=new ReturnResult();
        $data=$this->sortModel->getLeaf();
        $arr=array();
        $attr=array();
        foreach($data as $key=>$sort){
            /**@var $sort \Mirror\ApiBundle\Entity\Sort*/
            $sortId=$sort->getId();
            $sortAttrs=$this->sortAttrModel->getByProperty('sortId',$sortId);
            foreach ($sortAttrs as $sortAttr){
                /**@var $sortAttr \Mirror\ApiBundle\Entity\SortAttr*/
                $attr[$sortId][]=$sortAttr->getName();
            }
            $arr[]=array(
                'name'=>$sort->getName(),
                'id'=>$sortId
            );
        }
        $rr->result=array(
            'list'=>$arr,
            'attr'=>$attr
        );
        return $rr;
    }

    /**
     * @param $id
     * @return ReturnResult
     */
    public function delete($id){
        $rr=new ReturnResult();
        $sort=$this->sortModel->getById($id);
        /**@var $sort \Mirror\ApiBundle\Entity\Sort*/
        if(!$sort){
            $rr->errno=Code::$sort_not_exist;
            return $rr;
        }
        $goods=$this->goodsModel->getOneByCriteria(array('sortId'=>$id,'status'=>Constant::$status_normal));
        if($goods){
            $rr->errno=Code::$sort_has_goods;
            return $rr;
        }
        if($sort->getRightR()-$sort->getLeftR()>1){
            $rr->errno=Code::$sort_has_children_sort;
            return $rr;
        }
        $right=$sort->getRightR();
        try {
            $this->sortModel->getEntityManager()->beginTransaction();
            $this->sortModel->moveSortLeft($right,'sub');
            $this->sortModel->moveSortRight($right,'sub');
            $this->sortModel->delete($sort);
            $this->sortModel->getEntityManager()->commit();
        } catch (\Exception $e) {
            $this->sortModel->getEntityManager()->rollback();
            $rr->errno = Code::$sort_delete_fail;
            return $rr;
        }

        return $rr;
    }

    /**
     * @param $id
     * @return ReturnResult
     */
    public function getDetailList($id){
        $rr=new ReturnResult();
        $sort=$this->sortModel->getById($id);
        /**@var $sort \Mirror\ApiBundle\Entity\Sort*/
        if(!$sort){
            $rr->errno=Code::$sort_not_exist;
            return $rr;
        }
        $level=$sort->getLevel();
        $levelBack=$level;
        $left=$sort->getLeftR();
        $right=$sort->getRightR();
        $name=$sort->getName();
        $parentId=$sort->getParentId();
        $parentArr=array();
        $parentArr[]=array(
            'name'=>$name,
            'id'=>$id
        );
        while($levelBack>0){
            $parent=$this->sortModel->getById($parentId);
            $parentArr[]=array(
                'name'=>$parent->getName(),
                'id'=>$parentId
            );
            $parentId=$parent->getParentId();
            $levelBack=$parent->getLevel();
        }
        if($right-$left<=1){
            return $rr;
        }
        $list=$this->sortModel->getByParams(array('>'=>array('leftR'=>$left),'<'=>array('rightR'=>$right),'level'=>$level+1));
        $arr=array();
        foreach ($list as $sort){
            /**@var $sort \Mirror\ApiBundle\Entity\Sort*/
            $image=$sort->getImage();
            $level=$sort->getLevel();
            $left=$sort->getLeftR();
            $right=$sort->getRightR();
            if($right-$left>1){
                $count=$this->sortModel->getCountBy(array('>'=>array('leftR'=>$left),'<'=>array('rightR'=>$right),'level'=>$level+1));
            }else{
                $count=0;
            }
            $image=$this->fileService->getFullUrlById($image);
            $arr[]=array(
                'id'=>$sort->getId(),
                'name'=>$sort->getName(),
                'image'=>$image,
                'level'=>$sort->getLevel(),
                'count'=>$count
            );
        }
        $rr->result=array(
            'list'=>$arr,
            'parentArr'=>array_reverse($parentArr)
        );
        return $rr;
    }

}
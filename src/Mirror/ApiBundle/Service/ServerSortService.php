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
use Mirror\ApiBundle\Model\ServerModel;
use Mirror\ApiBundle\Model\ServerSortModel;
use Mirror\ApiBundle\Model\SortAttrModel;
use Mirror\ApiBundle\Model\SortModel;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use Mirror\ApiBundle\ViewModel\ReturnResult;

/**
 * @DI\Service("server_sort_service")
 * Class SortService
 * @package Mirror\ApiBundle\Service
 */
class ServerSortService
{
    private $serverSortModel;
    private $serverModel;
    private $fileService;

    /**
     * @InjectParams({
     * })
     * SortService constructor.
     * @param ServerSortModel $serverSortModel
     * @param FileService $fileService
     * @param ServerModel $serverModel
     */
    public function __construct(ServerSortModel $serverSortModel,FileService $fileService,ServerModel $serverModel)
    {
        $this->serverSortModel=$serverSortModel;
        $this->fileService=$fileService;
        $this->serverModel=$serverModel;
    }

    /**
     * 获取分类分组
     * @return ReturnResult
     */
    public function getList(){
        $rr=new ReturnResult();
        $sorts=$this->serverSortModel->getAll();
        $rr->result=array('list'=>$sorts);
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
     * @param $description
     * @return ReturnResult
     */
    public function add($name,$parentId,$image,$description){
        $rr=new ReturnResult();
        $parentSort=$this->serverSortModel->getById($parentId);
        /**@var $parentSort \Mirror\ApiBundle\Entity\Sort*/
        if(!$parentSort){
            $rr->errno=Code::$sort_not_exist;
            return $rr;
        }
        $right=$parentSort['right_r'];
        $level=$parentSort['level'];
        try {
            $this->serverSortModel->beginTransaction();
            $this->serverSortModel->moveSortLeft($right);
            $this->serverSortModel->moveSortRight($right);
            $data=array(
                'name'=>$name,
                'parent_id'=>$parentId,
                'logo'=>$image,
                'description'=>$description,
                'left_r'=>$right,
                'right_r'=>$right+1,
                'level'=>$level+1,
                'status'=>Constant::$status_normal,
                'create_time'=>date('Y-m-d H:i:s'),
                'update_time'=>date('Y-m-d H:i:s')
            );
            $sortId=$this->serverSortModel->save($data);
            $this->serverSortModel->commit();
        } catch (\Exception $e) {
            $this->serverSortModel->rollback();
            $rr->errno = Code::$sort_add_fail;
            return $rr;
        }
        $rr->result=array('sort'=>$sortId);
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

    /**
     * 获取所有叶子分类
     * @return ReturnResult
     */
    public function getLeaf(){
        $rr=new ReturnResult();
        $data=$this->serverSortModel->getLeaf();

        $rr->result=array(
            'list'=>$data
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
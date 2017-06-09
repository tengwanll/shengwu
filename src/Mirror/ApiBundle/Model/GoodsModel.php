<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/5/4
 * Time: 17:13
 */

namespace Mirror\ApiBundle\Model;

use Doctrine\ORM\Tools\Pagination\Paginator;
use JMS\DiExtraBundle\Annotation as DI;
use Mirror\ApiBundle\Util\QueryHelper;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @DI\Service("goods_model",parent="base_model")
 * Class OrderModel
 * @package Mirror\ApiBundle\Model
 */
class GoodsModel extends BaseModel
{
    private $repositoryName = 'MirrorApiBundle:Goods';

    public function getRepositoryName() {
        return $this->repositoryName;
    }

    public function getDetail($id,$conn){
        $sql='select name,sort_id,price,image,description,buy_num,status,create_time,update_time,column_json(attr) as attrs from goods where id='.$id;
        return $conn->fetchAssoc($sql);
    }
}
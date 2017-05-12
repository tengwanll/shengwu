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
 * @DI\Service("order_goods_model",parent="base_model")
 * Class OrderModel
 * @package Mirror\ApiBundle\Model
 */
class OrderGoodsModel extends BaseModel
{
    private $repositoryName = 'MirrorApiBundle:Order_goods';

    public function getRepositoryName() {
        return $this->repositoryName;
    }
}
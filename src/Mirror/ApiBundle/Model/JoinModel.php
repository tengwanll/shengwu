<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/6/1
 * Time: 14:13
 */

namespace Mirror\ApiBundle\Model;

use JMS\DiExtraBundle\Annotation as DI;
use Mirror\ApiBundle\Common\Constant;
use Mirror\ApiBundle\Util\QueryHelper;

/**
 * @DI\Service("join_model",parent="dbal_base_model")
 * Class CarModel
 * @package Mirror\ApiBundle\DbalBaseModel
 */
class JoinModel extends DbalBaseModel
{
    private $tableName = 'join_me';

    public function getTableName() {
        return $this->tableName;
    }
}
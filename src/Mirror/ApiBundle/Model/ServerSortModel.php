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

/**
 * @DI\Service("server_sort_model",parent="dbal_base_model")
 * Class CarModel
 * @package Mirror\ApiBundle\DbalBaseModel
 */
class ServerSortModel extends DbalBaseModel
{
    private $tableName = 'server_sort';

    public function getTableName() {
        return $this->tableName;
    }
}
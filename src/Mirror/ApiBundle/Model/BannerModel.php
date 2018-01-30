<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/12/18
 * Time: 10:24
 */

namespace Mirror\ApiBundle\Model;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("banner_model",parent="dbal_base_model")
 * Class BoxModel
 * @package Mirror\ApiBundle\Model
 */
class BannerModel extends DbalBaseModel
{
    private $tableName = 'banner';

    public function getTableName() {
        return $this->tableName;
    }
}
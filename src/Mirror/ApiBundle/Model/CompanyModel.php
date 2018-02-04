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
 * @DI\Service("company_model",parent="dbal_base_model")
 * Class BoxModel
 * @package Mirror\ApiBundle\Model
 */
class CompanyModel extends DbalBaseModel
{
    private $tableName = 'company';

    public function getTableName() {
        return $this->tableName;
    }
}
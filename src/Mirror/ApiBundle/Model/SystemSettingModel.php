<?php

namespace Mirror\ApiBundle\Model;

use JMS\DiExtraBundle\Annotation\Service;
use Mirror\ApiBundle\Common\Constant;
use Mirror\ApiBundle\Util\Helper;

/**
 * 系统设置
 * @Service("system_setting_model",parent="base_model")
 * Class SystemSettingModel
 * @package Mirror\ApiBundle\Model
 */
class SystemSettingModel extends BaseModel {

    private $repositoryName = 'MirrorApiBundle:SystemSettings';

    public function getRepositoryName() {
        return $this->repositoryName;
    }

    /**
     * 获取下载地址
     * @return string
     */
    public function getDownLoadBaseUrl() {
        $systemSetting = $this->getOneByProperty('name', Constant::$download_server);

        return $systemSetting ? $systemSetting->getValue() : '';
    }





    public function retrieve($arguments) {
        $id = Helper::getc($arguments, 'id', 0);
        $name = Helper::getc($arguments, 'name', '');
        $criteria = array();
        if ($id != 0) {
            $criteria = array_merge(
                $criteria,
                array(
                    'id' => $id,
                )
            );
        }
        if ($name != '') {
            $criteria = array_merge(
                $criteria,
                array(
                    'name' => $name,
                )
            );
        }
        if (count($criteria) == 0) {
            return 0;
        }
        return $this->getEntityManager()->getRepository('MirrorApiBundle:SystemSettings')->findOneBy($criteria);
    }
}
<?php

namespace Mirror\ApiBundle\Service;

use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use Mirror\ApiBundle\Common\Constant;
use Mirror\ApiBundle\Model\SystemSettingModel;
use Mirror\ApiBundle\ViewModel\ReturnResult;

/**
 * @Service("system_setting_service")
 * Class SystemSettingService
 * @package Mirror\ApiBundle\Service
 */
class SystemSettingService {

    private $systemSettingModel;

    /**
     * @InjectParams({
     *     "systemSettingModel" = @Inject("system_setting_model")
     * })
     * SystemSettingService constructor.
     * @param SystemSettingModel $systemSettingModel
     */
    public function __construct(SystemSettingModel $systemSettingModel) {
        $this->systemSettingModel = $systemSettingModel;
    }

    public function findByName($name, $default) {
        $systemSetting = $this->systemSettingModel->retrieve(
            array(
                'name' => $name,
            )
        );
        if ($systemSetting) {
            return $systemSetting->getValue();
        }

        return $default;
    }

    /**
     * 获取所有系统配置
     * @return ReturnResult
     */
    public function showSystem() {
        $rr = new ReturnResult();
        $params = array(
            // TODO 修改
            'showEditor' => 1,
            Constant::$FIELD_STATUS => Constant::$FIELD_STATUS_NORMAL,
        );

        $result = $this->systemSettingModel->getByCriteria($params);
        $arr = array();
        foreach ($result as $setting) {
            /** @var $setting \Mirror\ApiBundle\Entity\SystemSettings */
            $arr[] = array(
                'id' => $setting->getId(),
                'name' => $setting->getName(),
                'value' => $setting->getValue(),
                'description' => $setting->getDescription(),
            );
        }
        $rr->result = array(
            'rows' => $arr,
        );

        return $rr;
    }

    /**
     * 修改系统配置
     * @param $id
     * @param $value
     * @return ReturnResult
     */
    public function updateSystem($id, $value) {
        $rr = new ReturnResult();
        $set = $this->systemSettingModel->getById($id);
        if ($set) {
            $set->setValue($value);
            $this->systemSettingModel->flush($set);
        }

        return $rr;
    }

}
<?php

namespace Mirror\ApiBundle\Common;

/**
 * 常量类
 * Class Constant
 * @package Mirror\ApiBundle\Common
 */
class Constant {
    public static $login_entity='login_entity';

    //数据状态
    public static $status_forbidden=-3;
    public static $status_reject=-2;
    public static $status_delete=-1;
    public static $status_init=0;
    public static $status_normal=1;
    public static $status_dealing=2;
    public static $status_complete=3;

    //登录类型
    public static $login_type_admin=2;//后台用户
    public static $login_type_user=1;//前台用户

    //云之讯
    public static $UCPASS_ACCOUNT_SID='f87397ba589a9e33a8d9e2085a0e9705';
    public static $UCPASS_AUTH_TOKEN='987bd0296fa2bcde02b429fcd47733d2';
    public static $UCPAAS_APP_ID='dcd2fd41e1bf40d596c6fbc72cf472f7';
    public static $UCPAAS_TEMPLATE_ID_1='72741';//其他状态修改
    public static $UCPAAS_TEMPLATE_ID_2='127201';//被驳回

    //收藏类型
    public static $collection_credit=1;
    public static $collection_property=2;
    public static $collection_reward=3;

    //周期
    public static $cycle_week=1;
    public static $cycle_month=2;

    //驳回类型

    public static $reject_property=2;
    public static $reject_reward=3;
    public static $reject_credit=4;
    public static $reject_company=5;
    public static $reject_provincial_agents=6;
    public static $reject_city_agents=7;
    public static $reject_user=8;
    public static $reject_admin=9;

    //token有效期
    public static $access_token_expires=7200;
    public static $refresh_token_expires=86400;

    //消息
    public static $field_check_has=1;
    public static $field_check_no=0;


    //20170516创建
    public static $download_server = 'DOWNLOAD_SERVER';

    //美容
    public static $abilities=array(
        '1'=>'皮肤抗紫外线能力',
        '2'=>'皮肤抗敏感能力',
        '3'=>'皮肤抗氧化能力',
        '4'=>'皮肤锁水能力',
        '5'=>'皮肤色斑风险',
        '6'=>'皮肤弹性'
    );
}
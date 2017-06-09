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
    public static $UCPASS_ACCOUNT_SID='9132275cfdbaf5a449673bd6aa2389d1';
    public static $UCPASS_AUTH_TOKEN='1c0f888f3002051bcf6f495455ce99b5';
    public static $UCPAAS_APP_ID='e30ea705c35b43fdb14062b7fab92b32';
    public static $UCPAAS_TEMPLATE_ID='27678';
    public static $UCPAAS_RESETPWD_TEMPLATE_ID='30036';

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
}
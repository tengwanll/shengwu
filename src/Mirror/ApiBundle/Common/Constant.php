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

    //资产类型
    public static $property_house=0;
    public static $property_land=1;
    public static $property_equipment=2;
    public static $property_stock=3;
    public static $property_car=4;
    public static $property_patent=5;
    public static $property_copyright=6;
    public static $property_other=7;

    //资产售让类型
    public static $property_sell_pattern=0;//固定资产
    public static $property_sell_pattern_package=1;//资产包

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

    //合作机构
    public static $organization_urge=0;//处置机构
    public static $organization_cooperation=1;//合作律所展示
    public static $organization_credit=2;//担保机构展示
    public static $organization_assess=3;//评估担保机构

    //法务支持
    public static $laws_content=0;//相关法律法规内容
    public static $laws_cooperate=1;//法律合同
    public static $laws_lawyer=2;//律师
    //电话号码查询
    public static $telephonekey='c2281f0ba76064e4da369ff8a51e50a4';//查询手机号码的key
    //身份证查询
    public static $IDkey='0ba8d2d3e7ba369cf7e06bcfc21ac170';//查询身份证的key
    //个推
    public static $push_ge_host='https://api.getui.com/apiex.htm';
    public static $push_ge_ios_app_id='';
    public static $push_ge_android_app_id='';
    public static $push_ge_ios_app_key='';
    public static $push_ge_android_app_key='';
    public static $push_ge_ios_master_secret='';
    public static $push_ge_android_master_secret='';
    public static $push_to_single='';
    public static $push_to_all='';
    public static $field_checked_no=0;
    public static $field_status_normal=1;


    //token有效期
    public static $access_token_expires=7200;
    public static $refresh_token_expires=86400;

    //消息
    public static $field_check_has=1;
    public static $field_check_no=0;

    //微信
    public static $wx_appId='wxd4bdfb6c54fd53b9';
    public static $wx_app_secret='2f9f7fabfb5a3420089da126854562ee ';

    //公司类型
    public static $company_type_urge=1;//催收公司
    public static $company_type_proxy=2;//代理商

    //代理商
    public static $proxy_province=2;//省代理
    public static $proxy_city=3;//市代理

}
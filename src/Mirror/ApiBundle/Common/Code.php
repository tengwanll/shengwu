<?php
/**
 * Created by PhpStorm.
 * User: rpzhan
 * Date: 15/12/2
 * Time: 13:58
 */

namespace Mirror\ApiBundle\Common;

/**
 * 错误码
 * Class Code
 * @package Mirror\ApiBundle\Common
 */
class Code {
    public static $order_not_exist=20100;
    public static $goods_not_exist=20101;
    public static $sort_not_exist=20102;
    public static $sort_add_fail=20103;
    public static $file_not_exist=20104;
    public static $user_not_exist=20105;
    public static $user_forbidden=20106;
    public static $password_not_right=20107;
    public static $car_not_exist=20108;
    public static $number_not_right=20109;
    public static $create_order_fail=20110;
    public static $file_not_right_excel=20111;
    public static $file_name_null=20112;
    public static $file_sort_not_exist=20113;
    public static $file_price_null=20114;
    public static $mobile_already_exist=20115;
    public static $user_not_login=20116;
    public static $permission_reject=20117;
    public static $goods_had_exist=20118;
    public static $goods_had_on=20119;
    public static $sort_has_goods=20120;
    public static $sort_has_children_sort=20121;
    public static $sort_delete_fail=20122;
}
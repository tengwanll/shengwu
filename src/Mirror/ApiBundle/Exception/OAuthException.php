<?php
/**
 * Created by PhpStorm.
 * User: rpzhan
 * Date: 15/12/4
 * Time: 18:55
 */

namespace Mirror\ApiBundle\Exception;

use Mirror\ApiBundle\Common\Code;

class OAuthException extends LogicException {

    /**
     * OAuthException constructor.
     */
    public function __construct() {
        parent::__construct(Code::$user_not_login);
    }
}
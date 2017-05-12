<?php

namespace Mirror\ApiBundle\Exception;

class PermissionException extends LogicException{
	public function __construct(){
		parent::__construct(20403);
	}
}

?>
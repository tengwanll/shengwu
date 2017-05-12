<?php

namespace Mirror\ApiBundle\Exception;

class CustomException extends \Exception{
	public function __construct($code,$message){
		parent::__construct($message,$code);
	}
}

?>
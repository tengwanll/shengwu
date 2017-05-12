<?php

namespace Mirror\ApiBundle\Exception;

use Mirror\ApiBundle\Util\Helper;
class LogicException extends \Exception {
	private $errno;
	private $errmsg;
	
	public function __construct($errno){
		$this->errno = $errno;
		$this->setErrmsg();
	}
	
	public function getErrno()
	{
		return $this->errno;
	}
	
	public function getErrmsg()
	{
		return $this->errmsg;
	}
	
	private function setErrmsg()
	{
		$path = $this->getConfigPath();
		$str = file_get_contents($path);
		$json = json_decode($str,true);
		$this->errmsg = Helper::getc($json,$this->errno,'');
	}
	
	private function getConfigPath() {
		$dir = __DIR__;
		$os = php_uname ( 's' );
		$os = strtoupper ( $os );
		$split = "/";
		if (strpos ( $os, 'WINDOWS' )) {
			$split = "\\";
		}
		return $dir . $split . 'exception.json';
	}
	
	public function toString()
	{
		$json_r = array(
				'errno' => $this->errno,
				'errmsg' => $this->errmsg
		);
		$str = json_encode($json_r);
		return $str;
	}
}

?>
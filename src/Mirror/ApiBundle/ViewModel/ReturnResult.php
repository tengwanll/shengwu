<?php
namespace Mirror\ApiBundle\ViewModel;

class ReturnResult{
	public $errno;
	public $result;
	public $errmsg;
	
	public function __construct($errno =0,$result = array(),$errmsg='')
	{
		$this->errno = $errno;
		$this->result = $result;
		$this->errmsg=$errmsg;
	}
}
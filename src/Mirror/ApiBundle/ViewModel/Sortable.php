<?php
namespace Mirror\ApiBundle\ViewModel;

class Sortable
{
	private $sort;
	private $order;
	
	public function __construct($sort = array(),$order = array())
	{
		$this->sort = $sort;
		$this->order = $order;
	}
	
	public function setOrder($order)
	{
		$this->order = $order;
		return $this;
	}
	
	public function getOrder()
	{
		return $this->order;
	}
	
	public function setSort($sort)
	{
		$this->sort = $sort;
		return  $this;
	}
	
	public function getSort()
	{
		return $this->sort;
	}
}
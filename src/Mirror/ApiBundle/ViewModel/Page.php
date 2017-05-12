<?php

namespace Mirror\ApiBundle\ViewModel;

class Page {
	private $rows;
	private $total;
	public function setRows($rows) {
		$this->rows = $rows;
		return $this;
	}
	public function getRows() {
		return $this->rows;
	}
	public function setTotal($total) {
		$this->total = $total;
		return $this;
	}
	public function getTotal() {
		return $this->total;
	}
}
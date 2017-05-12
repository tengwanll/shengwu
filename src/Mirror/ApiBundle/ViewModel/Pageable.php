<?php

namespace Mirror\ApiBundle\ViewModel;

class Pageable {
	private $page;
	private $rows;
	public function __construct($page = 0, $rows = 0) {
		$this->page = $page;
		$this->rows = $rows;
	}
	public function setPage($page) {
		$this->page = $page;
	}
	public function getPage() {
		return $this->page;
	}
	public function setRows($rows) {
		$this->rows = $rows;
	}
	public function getRows() {
		return $this->rows;
	}
}

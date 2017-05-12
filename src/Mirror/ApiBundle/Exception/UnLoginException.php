<?php

namespace Mirror\ApiBundle\Exception;


class UnLoginException extends LogicException {
	public function __construct() {
		parent::__construct(20404);
	}
}


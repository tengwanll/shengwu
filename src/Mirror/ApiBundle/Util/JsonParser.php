<?php

namespace Mirror\ApiBundle\Util;

use Symfony\Component\HttpFoundation\Request;

class JsonParser {
	private $json;
	private $method;
	public function parse(Request $request,$method = 'json') {
		if($method=='json'){
			$data = $request->getContent ();
            $this->json = json_decode ( $data, true );
			$this->method = $method;
			return $this;
		}
		$this->method = $method;
		$this->json = $request;
		return $this;
	}
	public function get($param, $default = '', $notExpected = '') {
		$method = $this->method;
		if($method == 'json'){
			if (! $this->json) {
				return $default;
			}
			if (! isset ( $this->json [$param] )) {
				return $default;
			}
			
			if ($this->json [$param] === $notExpected) {
				return $default;
			}
			
			if (is_array ( $this->json [$param] )) {
				return $this->json [$param];
			}
			
			return trim ( $this->json [$param] );
		}
		
		if($method=='get')
		{
			return $this->json->request->get($param,$default);
		}
		
		if($method=='post')
		{
			return $this->json->request->get($param,$default);
		}
		return $default;
	}
	public function getAll(){
		return $this->json;
	}
}
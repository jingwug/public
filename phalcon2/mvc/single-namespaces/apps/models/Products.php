<?php

namespace Single\Models;

class Products extends \Phalcon\Mvc\Model {

	public function initialize(){
		$this->setSource('products');
	}
	
}
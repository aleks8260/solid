<?php
class Product {
    private $logger;
    public function __construct(Logger $logger){
        $this->logger = $logger;
        
    }
	public function setPrice($price) {
	    try {
	        // сохраняем цену в БД
	    } catch (DbException $e) {
	        $this->Logger->logError($e->getMessage());
	    }
	}
}
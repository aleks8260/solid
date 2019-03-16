<?php


// Single responsibility
$product = new Product();
$product->setPrice(100);

class Product {
	public function setPrice($price) {
	    try {
	        // сохраняем цену в БД
	    } catch (DbException $e) {
	        $this->logError($e->getMessage());
	    }
	}
	 
	public function logError($error) {
	    // сохраняем лог
	    }
}

// Open/closed - Реализация этого open closed принципа
$logger = new Logger();
$product = new Product($logger);
$product->setPrice(100);


// Interface segregation - применить принцип
// ITransformer.php
interface ISuperTransformer
{
    public function toCar();
    public function toPlane();
    public function toShip();
}
// transformers.php
class SuperTransformer implements ISuperTransformer {
    public function toCar(){
        echo 'transform to car';
    }
     
    public function toPlane(){
        echo 'transform to plane';
    }
     
    public function toShip(){
        echo 'transform to ship';
    }
}
 
class CarTransformer implements ISuperTransformer {
    public function toCar(){
        echo 'transform to car';
    }
     
    public function toPlane(){
        throw new Exception('i can`t transform to plane');
    }
     
    public function toShip(){
        throw new Exception('i can`t transform to ship');
    }
}

// Dependency Inversion
// husband.php 
class lowRankingMale {
    public function eat() {
        $wife = new Wife();
        $food = $wife->getFood();
        // ... eat
    }
}

// wife.php
class Wife {
    public function getFood() {
        // ...
        return $food;
    }
}
/*
В данной системе низкоранговый самец - экземпляр класса lowRankingMale жестко зависит от класса Wife причем у него даже нет возможности выбора.*/
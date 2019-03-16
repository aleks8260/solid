<?php
// Роберт Мартин «Быстрая разработка программ».
// https://habr.com/ru/post/208442/ 
// Single responsibility - Problem
// Принцип единственной ответственности
class Order
{
	public function calculateTotalSum(){/*...*/}
	public function getItems(){/*...*/}
	public function getItemCount(){/*...*/}
	public function addItem($item){/*...*/}
	public function deleteItem($item){/*...*/}

	public function printOrder(){/*...*/}
	public function showOrder(){/*...*/}

	public function load(){/*...*/}
	public function save(){/*...*/}
	public function update(){/*...*/}
	public function delete(){/*...*/}
}

// Single responsibility - Decision
class Order
{
	public function calculateTotalSum(){/*...*/}
	public function getItems(){/*...*/}
	public function getItemCount(){/*...*/}
	public function addItem($item){/*...*/}
	public function deleteItem($item){/*...*/}
}

class OrderRepository
{
	public function load($orderID){/*...*/}
	public function save($order){/*...*/}
	public function update($order){/*...*/}
	public function delete($order){/*...*/}
}

class OrderViewer
{
	public function printOrder($order){/*...*/}
	public function showOrder($order){/*...*/}
}



// Open-closed - problem
// Принцип открытости/закрытости
class OrderRepository
{
	public function load($orderID)
	{
		$pdo = new PDO($this->config->getDsn(), $this->config->getDBUser(), $this->config->getDBPassword());
		$statement = $pdo->prepare('SELECT * FROM `orders` WHERE id=:id');
		$statement->execute(array(':id' => $orderID));
		return $query->fetchObject('Order');	
	}

	public function save($order){/*...*/}
	public function update($order){/*...*/}
	public function delete($order){/*...*/}
}

// Open-closed - decision
class OrderRepository
{
	private $source;

	public function setSource(IOrderSource $source)
	{
		$this->source = $source;
	}

	public function load($orderID)
	{
		return $this->source->load($orderID);
	}
	public function save($order){/*...*/}
	public function update($order){/*...*/}
}

interface IOrderSource
{
	public function load($orderID);
	public function save($order);
	public function update($order);
	public function delete($order);
}

class MySQLOrderSource implements IOrderSource
{
	public function load($orderID);
	public function save($order){/*...*/}
	public function update($order){/*...*/}
	public function delete($order){/*...*/}
}

class ApiOrderSource implements IOrderSource
{
	public function load($orderID);
	public function save($order){/*...*/}
	public function update($order){/*...*/}
	public function delete($order){/*...*/}
}



// Liskov substitution - problem
// Принцип подстановки Барбары Лисков
class Rectangle
{
	protected $width;
	protected $height;

	public setWidth($width)
	{
		$this->width = $width;
	}

	public setHeight($height)
	{
		$this->height = $height;
	}

	public function getWidth()
	{
		return $this->width;
	}

	public function getHeight()
	{
		return $this->height;
	}
}

class Square extends Rectangle
{
	public setWidth($width)
	{
		parent::setWidth($width);
		parent::setHeight($width);
	}

	public setHeight($height)
	{
		parent::setHeight($height);
		parent::setWidth($height);
	}
}

function calculateRectangleSquare(Rectangle $rectangle, $width, $height)
{
	$rectangle->setWidth($width);
	$rectangle->setHeight($height);
	return $rectangle->getHeight * $rectangle->getWidth;
}

calculateRectangleSquare(new Rectangle, 4, 5); // 20
calculateRectangleSquare(new Square, 4, 5); // 25 ???

/*

Ослабили мы их вот почему. Если за постусловие метода setWidth принять (($this->width == $width) && ($this->height == $oldHeight)) ($oldHeight мы присвоили вначале метода setWidth), то это условие не выполняется в дочернем классе и соответственно мы его ослабили и принцип Лисков нарушен.

*/

// Liskov substitution - decision
class Rectangle
{
	protected $width;
	protected $height;

	public setWidth($width)
	{
		$this->width = $width;
	}

	public setHeight($height)
	{
		$this->height = $height;
	}

	public function getWidth()
	{
		return $this->width;
	}

	public function getHeight()
	{
		return $this->height;
	}
}

class Square
{
	protected $size;
	
	public setSize($size)
	{
		$this->size = $size;
	}

	public function getSize()
	{
		return $this->size;
	}
}

// Interface segregation - problem
// Принцип разделения интерфейса
interface IItem
{
	public function applyDiscount($discount);
	public function applyPromocode($promocode);

	public function setColor($color);
	public function setSize($size);
	
	public function setCondition($condition);
	public function setPrice($price);
}

// Interface segregation - decision
interface IItem
{
	public function setCondition($condition);
	public function setPrice($price);
}

interface IClothes
{
	public function setColor($color);
	public function setSize($size);
	public function setMaterial($material);
}

interface IDiscountable
{
	public function applyDiscount($discount);
	public function applyPromocode($promocode);
}

class Book implemets IItem, IDiscountable
{
    public function setCondition($condition){/*...*/}
    public function setPrice($price){/*...*/}
    public function applyDiscount($discount){/*...*/}
    public function applyPromocode($promocode){/*...*/}
}

class KidsClothes implemets IItem, IClothes
{
    public function setCondition($condition){/*...*/}
    public function setPrice($price){/*...*/}
    public function setColor($color){/*...*/}
    public function setSize($size){/*...*/}
    public function setMaterial($material){/*...*/}
}

// Dependency Invertion - problem
// Принцип инверсии зависимостей
class Customer
{
	private $currentOrder = null;

	public function buyItems()
	{	
		if(is_null($this->currentOrder)){
			return false;
		}

		$processor = new OrderProcessor();
		return $processor->checkout($this->currentOrder);	
	}

	public function addItem($item){
		if(is_null($this->currentOrder)){
			$this->currentOrder = new Order();
		}
		return $this->currentOrder->addItem($item);
	}
	public function deleteItem($item){
		if(is_null($this->currentOrder)){
			return false;
		}
		return $this->currentOrder ->deleteItem($item);
	}
}

class OrderProcessor
{
	public function checkout($order){/*...*/}
}

// Dependency Invertion - problem
class Customer
{
	private $currentOrder = null;

	public function buyItems(IOrderProcessor $processor)
	{	
		if(is_null($this->currentOrder)){
			return false;
		}
		
		return $processor->checkout($this->currentOrder);	
	}

	public function addItem($item){
		if(is_null($this->currentOrder)){
			$this->currentOrder = new Order();
		}
		return $this->currentOrder->addItem($item);
	}
	public function deleteItem($item){
		if(is_null($this->currentOrder)){
			return false;
		}
		return $this->currentOrder->deleteItem($item);
	}
}

interface IOrderProcessor
{
	public function checkout($order);
}

class OrderProcessor implements IOrderProcessor
{
	public function checkout($order){/*...*/}
}
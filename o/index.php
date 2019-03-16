<?php

// Open/closed
interface ILogger {
public function log();
}

class FileLogger implements ILogger {

private function saveToFile($message) {
//...
}
public function log($message) {
//...
$this->saveToFile($message);
}
}

class DBLogger implements ILogger {

private function saveToDB($message) {
//...
}
public function log($message) {
//...
$this->saveToDB($message);
}
}

class Product {
protected $logger;

public function __construct(ILogger $logger) {
$this->logger = $logger;
}

public function setPrice($price) {
try {
// save price in db
} catch (DbException $e) {
$this->logger->log($e->getMessage());
}
}
}

$loggerDB  = new DBLogger();
$product = new Product($loggerDB);

$loggerFile  = new FileLogger();
$post = new Post($loggerFile);
$product->setPrice(10);
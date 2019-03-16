<?php


// Single responsibility
$logger = new Logger();
$product = new Product($logger);
$product->setPrice(100);
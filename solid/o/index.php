<?php


// Single responsibility
$logger = new Logger();
$product = new Product($logger);
$post = new Post($logger);
$product->setPrice(100);
<?php
session_start();
require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/app/php.php');
require_once(__DIR__ . '/app/controller/vending.php');
require_once(__DIR__ . '/app/model/vending.php');

$page = new VendingController();
$page->index();
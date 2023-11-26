<?php

require_once 'C:\xampp\htdocs\learn.php\ORM\vendor\autoload.php';


use App\Helpers\Config;
use App\DataBase\PDODataBaseConnection;



$pdo = new PDODataBaseConnection(Config::get('DataBase', 'pdo_testing'));
$pdoHandler  = $pdo->connect();














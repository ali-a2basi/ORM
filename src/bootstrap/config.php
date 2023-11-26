<?php
use App\Helpers\Config;
use App\DataBase\PDODataBaseConnection;
use App\DataBase\PDOQueryBuilder;

require_once 'C:\xampp\htdocs\learn.php\ORM\vendor\autoload.php';


$pdoConfig = Config::get('Database', 'pdo_testing');
$pdoDataBaseConnection = (new PDODataBaseConnection($pdoConfig));
$pdoQueryBuilder = new PDOQueryBuilder($pdoDataBaseConnection->connect());
$pdoQueryBuilder->truncateAllTable();
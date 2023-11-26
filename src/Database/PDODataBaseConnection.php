<?php

namespace App\DataBase;
use App\Contracts\DataBaseConnectionInterface;
use App\Exceptions\ConfigMissingRequiredKeyException;
use App\Exceptions\DatabaseConnectionException;
use PDO;
use PDOException;

class PDODataBaseConnection implements DataBaseConnectionInterface {
    public  $connection;
    public  $config;

    const REQUIRED_KEY_CONFIG = 
    [
        'driver',
        'host',
        'database',
        'db_username',
        'db_password'
    ];
    

    public function __construct(array $config)
    {

        if(!self::isConfigValid($config)){

            throw new ConfigMissingRequiredKeyException();
        }
        $this->config = $config;

    }
    public function connect()
    {
        if (!$this->connection) {
            $dsn = $this->generateDsn($this->config);

        

            try {
                
                $this->connection = new PDO(...$dsn);
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);


            } catch(PDOException $e) {
                throw new DatabaseConnectionException($e->getLine());
            }

        }
        
        
        return $this;
    }
    public function getConnection()
    {
        $this->connect();
        
        return $this->connection;

    }

    private function generateDsn(array $config){

        $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']}";



        return [$dsn, $config['db_username'], $config['db_password']];

        
    }
    public function isConfigValid(array $config)
    {

        $match = array_intersect(self::REQUIRED_KEY_CONFIG, array_keys($config));
        
        return count($match) === count(self::REQUIRED_KEY_CONFIG);

    }

}
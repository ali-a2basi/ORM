<?php
namespace App\DataBase;

use App\Contracts\DataBaseConnectionInterface;
use PDO;

class PDOQueryBuilder{
    protected $connection;
    protected $table;
    protected $conditions;
    protected $values;
    protected $update;
    protected $placeHolder;
    
    public function __construct(DataBaseConnectionInterface $connection)
    {
        $this->connection = $connection->getConnection();
        
        
    }


    public function table(string $table){
         $this->table = $table;
         return $this;
    
        
    }

    public function create(array $data){
        
        
        $placeHolder = [];
                
        foreach($data as $column => $value){

            $placeHolder[] = "?";
            
        }

        $placeHolder = implode(',', $placeHolder);
        

        $arrayKeys = implode(',', array_keys($data));

        $sql = "INSERT INTO `{$this->table}` ({$arrayKeys}) VALUES ({$placeHolder})";
        
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(array_values($data));

        return (int)$this->connection->lastInsertId();
    }


    public function where(string $column, $value){
        
        $this->conditions[] = "{$column} = ?";
        $this->values = $value;
        
        return $this;
    }

    public function update(array $data){
        
        $fields = [];
        foreach($data as $setKey => $setValue){
            $fields[] = "{$setKey} = '{$setValue}'";
        }
        $fields = implode(',', $fields);
        $conditions = implode('and', $this->conditions);


        $sql = "UPDATE {$this->table} SET {$fields} WHERE $conditions";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$this->values]);
        return $stmt->rowCount();

    }

    public function delete(){
        $conditions = implode(' and ', $this->conditions);
        $sql = "DELETE FROM `{$this->table}` WHERE $conditions";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$this->values]);
        return $stmt->rowCount();

    }

    public function get( array $columns = ['*']){
        $condition = implode('and', $this->conditions);
        $columns = implode(',', $columns);
        $sql = "SELECT {$columns} FROM {$this->table} WHERE {$condition}";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$this->values]);
        return $stmt->fetchAll();
    }

    public function first(){

        $data = $this->get($columns = ['*']);

        return empty($data) ? null : $data[0];
    }


    public function find( int $id){

        return $this->where('id', $id)->first();
    }


    public function findBy(string $columns, $value){
        return $this->where($columns, $value)->first();


    }


    public function truncateAllTable(){

        $sql = $this->connection->prepare('SHOW TABLES');
        $sql->execute();

        foreach($sql->fetchAll(PDO::FETCH_COLUMN) as $table){
            $truncateQuery = $this->connection->prepare("TRUNCATE TABLE `{$table}`");
            $truncateQuery->execute();
        }
    }

    public function beginTransaction(){

        $this->connection->beginTransaction();
    }


    public function rollBack(){
        $this->connection->rollBack();
    }


}
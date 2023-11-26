<?php declare(strict_types=1);
namespace Tests\Unit;

use App\Contracts\DatabaseGetConfigTrait;
use PHPUnit\Framework\TestCase;
use App\Helpers\Config;
use App\DataBase\PDODataBaseConnection;
use App\Contracts\DataBaseConnectionInterface;
use App\Exceptions\ConfigMissingRequiredKeyException;
Use PDO;


class PDODataBaseConnectionTest extends TestCase{

    use DatabaseGetConfigTrait;


    public function testPDODataBaseConnectionImplementsDataBaseConnectionInterface(){
        $config = $this->getConfig();

        $pdoDataBaseConnection = new PDODataBaseConnection($config);
        $this->assertInstanceOf(DataBaseConnectionInterface::class, $pdoDataBaseConnection);

        
    }


    public function testIfGetConnectionMethodIsAInstanceOfPDO()
    {
        $config = $this->getConfig();
        $pdoDataBase = new PDODataBaseConnection($config);
        $pdoDataBase->connect();

        $this->assertInstanceOf(PDO::class, $pdoDataBase->getConnection());
    }

    public function testIfConnectMethodReturnsValidInstance(){

        $config = $this->getConfig();
        $pdoDataBaseConnection = new PDODataBaseConnection($config);
        $pdoHandler = $pdoDataBaseConnection->connect();

        $this->assertInstanceOf(PDODataBaseConnection::class, $pdoHandler);
    }


    public function testIfConfigRecievedRequiredKey()
    {
        $this->expectException(ConfigMissingRequiredKeyException::class);
        $config = $this->getConfig();
        unset($config['db_username']);
        unset($config['db_password']);
        $pdoDataBaseConnection = new PDODataBaseConnection($config);
        $pdoDataBaseConnection->connect();

    }
}




<?php declare(strict_types=1);
namespace Tests\Unit;


use App\Helpers\Config;
use App\Exceptions\ConfigFileNotFoundException;
use PHPUnit\Framework\TestCase;


class ConfigTest extends TestCase{


    public function testGetFileContentsReturnArray(){
        $arrayConfig = Config::getFileContents('Database');

        $this->assertIsArray($arrayConfig);
    }


    public function testThrowExceptionIfFilePathNotFound(){
//resolves a class name to its fully-qualified class name ::class
        $this->expectException(ConfigFileNotFoundException::class);

        $exceptionConfig = Config::getFileContents('pdoo');
        
    }


    public function testGetMethodReturnsValidData(){

        $config = Config::get('Database', 'pdo');
        $expectedData = [
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'bug_tracker',
            'db_username' => 'root',
            'db_password' => ''
        ];

        $this->assertEquals($config, $expectedData);
    }


    public function testIfKeyNotExistReturnTheContent(){
        $content = Config::getFileContents('DataBase');
        $config = Config::get('DataBase');
        $this->assertEquals($content, $config);
    }
}





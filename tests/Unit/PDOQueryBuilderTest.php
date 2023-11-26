<?php
namespace Tests\Unit;

use App\DataBase\PDODataBaseConnection;
use App\Contracts\DatabaseGetConfigTrait;
use App\DataBase\PDOQueryBuilder;
use PHPUnit\Framework\TestCase;





class PDOQueryBuilderTest extends TestCase
{
    use DatabaseGetConfigTrait;

    private $pdoQueryBuilder;

    public function setUp(): void
    {
        $pdoConnection = new PDODataBaseConnection($this->getConfig());
        $this->pdoQueryBuilder = new PDOQueryBuilder($pdoConnection->connect());
        $this->pdoQueryBuilder->beginTransaction();        
        parent::setUp();

    }
    
    public function testItCanCreateData(){


        $this->setUp();
        $result = $this->insertDataIntoDB();        
        $this->assertIsInt($result);
        $this->assertGreaterThan(0, $result);
        
    }


    public function insertDataIntoDB($option = []){
        
        $data = array_merge([

            'description' => 'The bug report ...',
            'link' => 'https://link.com',
            'username' => 'ali',
            'email' => 'ali@gmail.com'
        ], $option);
        
        return $this->pdoQueryBuilder->table('bugs')->create($data); 
        
    }



    public function testItCanUpdataData(){
        $this->insertDataIntoDB();

        $result = $this->pdoQueryBuilder->table('bugs')->where('username', 'ali')->update(['email' => 'ali12345@gmail.com',
                                                                'link' => 'https://link12345.com']);
        $this->assertEquals(1, $result);

    }

    public function testItCanDeleteData(){
        $this->setUp();
        $this->multipleDataInsertion(4);

        $result = $this->pdoQueryBuilder->table('bugs')->where('username', 'ali')->delete();
        $this->assertEquals(4, $result);
    }

    public function testItCanFetchAllData(){
        $this->setUp();


        $this->multipleDataInsertion(10,['username' => 'name']);


        $result = $this->pdoQueryBuilder
            ->table('bugs')
            ->where('username', 'name')
            ->get();


        $this->assertIsArray($result);
        $this->assertCount(10, $result);
        }

    public function testIfGetFunctionCanReturnSpecifiedColumn(){
        $this->multipleDataInsertion(10);
        $this->multipleDataInsertion(10, ['username' => 'name']);

        $result = $this->pdoQueryBuilder
            ->table('bugs')
            ->where('username', 'name')
            ->get(['username', 'link']);



        $this->assertIsArray($result);
        $this->assertObjectHasProperty('username', $result[0]);
        $this->assertObjectHasProperty('link', $result[0]);


        $result = json_decode(json_encode($result[0]), true);
        $this->assertEquals(['username', 'link'], array_keys($result));
        }
        public function testItCanFetchFirstRecord(){

       
            $this->multipleDataInsertion(10, ['username' => 'name2']);
    
            $result = $this->pdoQueryBuilder
                ->table('bugs')
                ->where('username', 'name2')
                ->first();

            
            $this->assertIsObject($result);
            $this->assertObjectHasProperty('link', $result);
            $this->assertObjectHasProperty('description', $result);
            $this->assertObjectHasProperty('username', $result);
            $this->assertObjectHasProperty('email', $result);
    
        }

        public function testItCanFetchDataWithId(){

            $id = $this->insertDataIntoDB(['username' => 'for finding with id']);
            
            $result = $this->pdoQueryBuilder
                ->table('bugs')
                ->find($id);


            $this->assertIsObject($result);
            $this->assertEquals('for finding with id', $result->username);
            
        }

        public function testItCanFetchDataWithFindBy(){

            $id = $this->insertDataIntoDB(['username' => 'for finding with find by']);

            $result = $this->pdoQueryBuilder
                    ->table('bugs')
                    ->findBy('username','for finding with find by');

            $this->assertIsObject($result);

            $this->assertEquals($id, $result->id);
        }

        public function testItReturnsEmptyDataWithWrongData(){

            $result = $this->pdoQueryBuilder
                        ->table('bugs')
                        ->where('username', 'dummy')
                        ->get();


            $this->assertEmpty($result);
            $this->assertIsArray($result);
        }







    private function multipleDataInsertion($count, $option = []){

        for ($i = 1; $i<=$count; $i++){

            $this->insertDataIntoDB($option);
        }
    }




    public function tearDown(): void
    {


        $this->pdoQueryBuilder->rollBack();
        parent::tearDown();
    }
    

}
<?php

class PriceTest extends TestCase {
    public function setUp() {
        parent::setUp();
        DB::table('prices')->truncate();
    }

    public function tearDown() {
        DB::table('prices')->truncate();
        parent::tearDown();
    }

    public function testGetEmpty() {
        $this->get('api/price')->seeJsonEquals([]);
    }

    public function testNotExistingAccess() {
        $response = $this->call('GET','api/price/1');
        $this->assertEquals(404, $response->status());
    }

    public function testAddNew() {
        $this->post('api/price', ['product' => 'dinner', 'price' => 24])->seeJsonEquals(
            ['product' => 'dinner', 'price' => 24,'id' => 1]
        );
    }
    
    public function testDropExisting() {
        $this->post('api/price', ['product' => 'dinner', 'price' => 24]);
        
        $response = $this->call('DELETE', 'api/price/1');
        $this->assertEquals(200, $response->status());
    }

    public function testDropNotExisting() {
        $response = $this->call('DELETE', 'api/price/1');
        $this->assertEquals(404, $response->status());
    }
    
    public function testUpdateNotExisting() {
        $response = $this->call('PUT', 'api/price/1', ['product' => 'breakfast', 'price' => 11]);
        $this->assertEquals(404, $response->status());
    }

    public function testFullUpdate() {
        $this->post('api/price', ['product' => 'dinner', 'price' => 24]);
        $this->put('api/price/1', ['product' => 'breakfast', 'price' => 11]);
        $this->get('api/price/1')->seeJsonEquals(['id' => 1, 'product' => 'breakfast', 'price' => 11]);
    }    

    public function testPartialUpdate() {
        $this->post('api/price', ['product' => 'dinner', 'price' => 24]);
        $this->put('api/price/1', ['product' => 'breakfast']);
        $this->get('api/price/1')->seeJsonEquals(['id' => 1, 'product' => 'breakfast', 'price' => 24]);
    }  
}
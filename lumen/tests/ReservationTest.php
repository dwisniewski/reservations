<?php

class LocatorTest extends TestCase {
    public function setUp() {
        parent::setUp();
        DB::table('reservations')->truncate();
    }

    public function tearDown() {
        DB::table('reservations')->truncate();
        parent::tearDown();
    }

    public function testGetEmpty() {
        $this->get('api/reservation')->seeJsonEquals([]);
    }

    public function testNotExistingAccess() {
        $response = $this->call('GET','api/reservation/1');
        $this->assertEquals(404, $response->status());
    }


    public function testAddNew() {
        $this->post('api/reservation', ['name' => 'test', 'surname' => 'test2', 'email' => 'test@domain.com', 'phone' => '501-123-858'])->seeJsonEquals(
            ['id' => 1, 'name' => 'test', 'surname' => 'test2', 'email' => 'test@domain.com', 'phone' => '501-123-858']
        );
    }
    
    public function testDropExisting() {
        $this->post('api/reservation', ['name' => 'test', 'surname' => 'test2', 'email' => 'test@domain.com', 'phone' => '501-123-858']);
        
        $response = $this->call('DELETE', 'api/reservation/1');
        $this->assertEquals(200, $response->status());
    }

    public function testDropNotExisting() {
        $response = $this->call('DELETE', 'api/reservation/1');
        $this->assertEquals(404, $response->status());
    }


    public function testUpdateNotExisting() {
        $response = $this->call('PUT', 'api/reservation/1', ['name' => 'test', 'surname' => 'test2', 'email' => 'test@domain.com', 'phone' => '501-123-858']);
        $this->assertEquals(404, $response->status());
    }

    public function testFullUpdate() {
        $this->post('api/reservation', ['name' => 'test', 'surname' => 'test2', 'email' => 'test@domain.com', 'phone' => '501-123-858']);
        $this->put('api/reservation/1', ['name' => 'test2', 'surname' => 'test3', 'email' => 'test2@domain.com', 'phone' => '507-123-858']);
        $this->get('api/reservation/1')->seeJsonEquals(['id' => 1, 'name' => 'test2', 'surname' => 'test3', 'email' => 'test2@domain.com', 'phone' => '507-123-858']);
    }    

    public function testPartialUpdate() {
        $this->post('api/reservation', ['name' => 'test', 'surname' => 'test2', 'email' => 'test@domain.com', 'phone' => '501-123-858']);
        $this->put('api/reservation/1', ['name' => 'test2']);
        $this->get('api/reservation/1')->seeJsonEquals(['id' => 1, 'name' => 'test2', 'surname' => 'test2', 'email' => 'test@domain.com', 'phone' => '501-123-858']);
    }  


    /* room unavailability test */
}
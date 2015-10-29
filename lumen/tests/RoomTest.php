<?php

class RoomTest extends TestCase {
    public function setUp() {
        parent::setUp();
        DB::statement('DELETE FROM rooms');
        //DB::table('rooms')->truncate();
    }

    public function tearDown() {
        //DB::table('rooms')->truncate();
        DB::statement('DELETE FROM rooms');
        parent::tearDown();
    }

    public function testGetEmpty() {
        $this->get('api/room')->seeJsonEquals([]);
    }

    public function testNotExistingAccess() {
        $response = $this->call('GET','api/room/1');
        $this->assertEquals(404, $response->status());
    }


    public function testAddNew() {
        $this->post('api/room', ['number' => 1, 'capacity' => 24])->seeJsonEquals(
            ['capacity' => 24,'number' => 1]
        );
    }
    
    public function testDropExisting() {
        $this->post('api/room', ['number' => 1, 'capacity' => 24]);
        
        $response = $this->call('DELETE', 'api/room/1');
        $this->assertEquals(200, $response->status());
    }

    public function testDropNotExisting() {
        $response = $this->call('DELETE', 'api/room/1');
        $this->assertEquals(404, $response->status());
    }


    public function testUpdateNotExisting() {
        $response = $this->call('PUT', 'api/room/1', ['number' => 1, 'capacity' => 11]);
        $this->assertEquals(404, $response->status());
    }

    public function testFullUpdate() {
        $this->post('api/room', ['number' => 1, 'capacity' => 1]);
        $this->put('api/room/1', ['number' => 2, 'capacity' => 11]);
        $this->get('api/room/2')->seeJsonEquals(['number' => 2, 'capacity' => 11]);
    }    

    public function testPartialUpdate() {
        $this->post('api/room', ['number' => 1, 'capacity' => 1]);
        $this->put('api/room/1', ['capacity' => 2]);
        $this->get('api/room/1')->seeJsonEquals(['number' => 1, 'capacity' => 2]);
    }  


    /* room unavailability test */
}
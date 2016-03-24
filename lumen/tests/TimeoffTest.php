<?php

class TimeoffTest extends TestCase {
    public function setUp() {
        parent::setUp();
        DB::statement('DELETE FROM rooms');                     // because Timeoff is strongly connected with rooms - assure we recreate rooms correctly
        DB::table('rooms_unavailability')->truncate();
    }

    public function tearDown() {
        DB::statement('DELETE FROM rooms');
        DB::table('rooms_unavailability')->truncate();
        parent::tearDown();
    }

    public function testGetEmpty() {
        $this->get('api/timeoff')->seeJsonEquals([]);
    }

    public function testNotExistingAccess() {
        $response = $this->call('GET','api/timeoff/1');
        $this->assertEquals(404, $response->status());
    }


    public function testAddNew() {
        $this->post('api/room', ['number' => 1, 'capacity' => 24]);
        $this->post('api/timeoff', ['id' => 1, 'room_id' => 1, 'since' => '2014-01-01 18:00:00', 'till' => '2014-01-01 18:00:00'])->seeJsonEquals(
            ['id' => 1, 'room_id' => 1, 'since' => '2014-01-01 18:00:00', 'till' => '2014-01-01 18:00:00']
        );
    }
    
    public function testAddNewNonexistingRoom() {
        $response = $this->call('POST', 'api/timeoff', ['room_id' => 1, 'since' => '2014-01-01 18:00:00', 'till' => '2014-01-01 18:00:00']);
        $this->assertEquals(500, $response->status());
    }
    

    public function testDropExisting() {
        $this->post('api/room', ['number' => 1, 'capacity' => 24]);
        $this->post('api/timeoff', ['room_id' => 1, 'since' => '2014-01-01 18:00:00', 'till' => '2014-01-01 18:00:00'])->seeJsonEquals(
            ['id' => 1, 'room_id' => 1, 'since' => '2014-01-01 18:00:00', 'till' => '2014-01-01 18:00:00']
        );

        $response = $this->call('DELETE', 'api/timeoff/1');
        $this->assertEquals(200, $response->status());
    }

    public function testDropNotExisting() {
        $response = $this->call('DELETE', 'api/timeoff/1');
        $this->assertEquals(404, $response->status());
    }


    public function testUpdateNotExisting() {
        $response = $this->call('PUT', 'api/timeoff/1', ['since' => '2014-01-01 18:00:00', 'till' => '2014-01-01 18:00:00']);
        $this->assertEquals(404, $response->status());
    }

    public function testFullUpdate() {
        $this->post('api/room', ['number' => 1, 'capacity' => 24]);
        $this->post('api/room', ['number' => 2, 'capacity' => 24]);
        
        $this->post('api/timeoff', ['room_id' => 1, 'since' => '2014-01-01 18:00:00', 'till' => '2014-01-01 18:00:00']);
        $this->put('api/timeoff/1', ['room_id' => 2, 'since' => '2015-01-02 18:15:30', 'till' => '2015-01-05 18:22:50']);
        $this->get('api/timeoff/1')->seeJsonEquals(['id' => 1, 'room_id' => 2, 'since' => '2015-01-02 18:15:30', 'till' => '2015-01-05 18:22:50']);
    }    

    public function testPartialUpdate() {
        $this->post('api/room', ['number' => 1, 'capacity' => 24]);
        
        $this->post('api/timeoff', ['room_id' => 1, 'since' => '2014-01-01 18:00:00', 'till' => '2014-01-01 18:00:00']);
        $this->put('api/timeoff/1', ['since' => '2015-01-02 18:15:30']);
        $this->get('api/timeoff/1')->seeJsonEquals(['id'=> 1, 'room_id' => 1, 'since' => '2015-01-02 18:15:30', 'till' => '2014-01-01 18:00:00']);
     }  

    public function testPartialUpdateToNonexistingRoom() {
        $this->post('api/room', ['number' => 1, 'capacity' => 24]);
        
        $this->post('api/timeoff', ['room_id' => 1, 'since' => '2014-01-01 18:00:00', 'till' => '2014-01-01 18:00:00']);
        $response = $this->call('PUT', 'api/timeoff/1', ['room_id' => 2, 'till' => '2015-01-05 18:22:50']);
        $this->assertEquals(500, $response->status()); 
    }

    public function testTimeoffsForRoomCount() {
        $this->post('api/room', ['number' => 1, 'capacity' => 24]);
        $this->post('api/room', ['number' => 1, 'capacity' => 25]);
        $this->post('api/room', ['number' => 2, 'capacity' => 30]);

        $this->post('api/timeoff', ['room_id' => 1, 'since' => '2015-01-01 18:00:00', 'till' => '2015-01-01 18:00:00']);
        $this->post('api/timeoff', ['room_id' => 1, 'since' => '2014-01-01 18:00:00', 'till' => '2014-01-01 18:00:00']);

        $response = $this->call('GET', 'api/room/1?timeoffs=true');
     
        $this->assertEquals(2, count($response->getData(true)[0]['timeoffs']));   
    }
}
<?php

class ReservationTest extends TestCase {
    public function setUp() {
        parent::setUp();
        DB::statement('DELETE FROM reservations');
        DB::table('reservations_rooms')->truncate();
        DB::statement('DELETE FROM locators');
        DB::statement('DELETE FROM rooms');
        DB::statement('ALTER TABLE locators AUTO_INCREMENT = 1');
        DB::statement('ALTER TABLE reservations AUTO_INCREMENT = 1');
    }

    public function tearDown() {
        DB::statement('DELETE FROM reservations');
        DB::table('reservations_rooms')->truncate();
        DB::statement('DELETE FROM locators');
        DB::statement('DELETE FROM rooms');
        DB::statement('ALTER TABLE locators AUTO_INCREMENT = 1');
        DB::statement('ALTER TABLE reservations AUTO_INCREMENT = 1');
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
        $this->post('api/locator', ['name' => 'test', 'surname' => 'test2', 'email' => 'test@domain.com', 'phone' => '501-123-858']);
        $this->post('api/room', ['number' => 1, 'capacity' => 24]);
        $this->post('api/room', ['number' => 2, 'capacity' => 24]);
        $this->post('api/room', ['number' => 3, 'capacity' => 24]);
        $this->post('api/reservation', ['locator_id' => 1, 'rooms' => [1, 2, 3], 'since' => '0000-00-00 00:00:00.000000', 'till' => '0000-00-00 00:00:00.000000', 'is_paid' => 1, 'dinners_count' => 1, 'people_count' => 1])->seeJsonContains(
            ['id' => 1, 'locator_id' => 1, 'since' => '0000-00-00 00:00:00.000000', 'till' => '0000-00-00 00:00:00.000000', 'is_paid' => 1, 'dinners_count' => 1, 'people_count' => 1]
        );
    }
    
    public function testDropExisting() {
        $this->post('api/locator', ['name' => 'test', 'surname' => 'test2', 'email' => 'test@domain.com', 'phone' => '501-123-858']);
        $this->post('api/room', ['number' => 1, 'capacity' => 24]);
        $this->post('api/room', ['number' => 2, 'capacity' => 24]);
        $this->post('api/room', ['number' => 3, 'capacity' => 24]);
        $this->post('api/reservation', ['locator_id' => 1, 'rooms' => [1, 2, 3], 'since' => '0000-00-00 00:00:00.000000', 'till' => '0000-00-00 00:00:00.000000', 'is_paid' => 1, 'dinners_count' => 1, 'people_count' => 1]);
        
        $response = $this->call('DELETE', 'api/reservation/1');
        $this->assertEquals(200, $response->status());
    }

    public function testDropNotExisting() {
        $response = $this->call('DELETE', 'api/reservation/1');
        $this->assertEquals(404, $response->status());
    }


    public function testUpdateNotExisting() {
        $response = $this->call('PUT', 'api/reservation/1', ['locator_id' => 1, 'rooms' => [1, 2, 3], 'since' => '2008-01-01 00:00:01', 'till' => '2008-01-01 00:00:01', 'is_paid' => 1, 'dinners_count' => 1, 'people_count' => 1]);
        $this->assertEquals(404, $response->status());
    }

    public function testFullUpdate() {
        $this->post('api/locator', ['name' => 'test', 'surname' => 'test2', 'email' => 'test@domain.com', 'phone' => '501-123-858']);
        $this->post('api/room', ['number' => 1, 'capacity' => 24]);
        $this->post('api/room', ['number' => 2, 'capacity' => 24]);
        $this->post('api/room', ['number' => 3, 'capacity' => 24]);
        $this->post('api/reservation', [
            'locator_id' => 1, 
            'rooms' => [1, 2, 3], 
            'since' => '2007-01-01 00:00:01', 
            'till' => '2007-01-01 00:00:01', 
            'is_paid' => 1, 
            'dinners_count' => 1, 
            'people_count' => 1
            ]);
        $this->put('api/reservation/1', [
            'locator_id' => 1, 
            'rooms' => [1, 2], 
            'since' => '2008-01-01 00:00:01', 
            'till' => '2008-01-01 00:00:01', 
            'is_paid' => 2, 
            'dinners_count' => 2, 
            'people_count' => 2
            ]);
        $this->get('api/reservation/1?rooms=True')->seeJsonContains([
            'locator_id' => 1, 
            'id'=> 1, 
            'since' => '2008-01-01 00:00:01', 
            'till' => '2008-01-01 00:00:01', 
            'is_paid' => 2, 
            'dinners_count' => 2, 
            'people_count' => 2
            ]);
    }    

    public function testPartialUpdate() {
        $this->post('api/locator', ['name' => 'test', 'surname' => 'test2', 'email' => 'test@domain.com', 'phone' => '501-123-858']);
        $this->post('api/room', ['number' => 1, 'capacity' => 24]);
        $this->post('api/room', ['number' => 2, 'capacity' => 24]);
        $this->post('api/room', ['number' => 3, 'capacity' => 24]);
        $this->post('api/reservation', ['locator_id' => 1, 'rooms' => [1, 2, 3], 'since' => '2008-01-01 00:00:01', 'till' => '2008-01-01 00:00:01', 'is_paid' => 1, 'dinners_count' => 1, 'people_count' => 1]);
        $this->put('api/reservation/1', ['dinners_count' => 2]);
        $this->get('api/reservation/1?rooms=True')->seeJsonContains(['locator_id' => 1, 'id' => 1, 'since' => '2008-01-01 00:00:01', 'till' => '2008-01-01 00:00:01', 'is_paid' => 1, 'dinners_count' => 2, 'people_count' => 1]);
    }  
    /* room unavailability test */
}

<?php

class UserTest extends TestCase {
    public function setUp() {
        parent::setUp();
        DB::table('users')->truncate();
    }

    public function tearDown() {
        DB::table('users')->truncate();
        parent::tearDown();
    }

    public function testGetEmpty() {
        $this->get('api/user')->seeJsonEquals([]);
    }

    public function testNotExistingAccess() {
        $response = $this->call('GET','api/user/1');
        $this->assertEquals(404, $response->status());
    }

    public function testAddNew() {
        $this->post('api/user', ['login' => 'Dawid', 'password' => 'test', 'email' => 'test@domain.com'])->seeJsonEquals(
            ['login' => 'Dawid', 'password' => md5('test'),'id' => 1, 'email' => 'test@domain.com']
        );
    }
    
    public function testDropExisting() {
        $this->post('api/user', ['login' => 'Dawid', 'password' => 'test', 'email' => 'test@domain.com']);
        $response = $this->call('DELETE', 'api/user/1');
        $this->assertEquals(200, $response->status());
    }

    public function testDropNotExisting() {
        $response = $this->call('DELETE', 'api/user/1');
        $this->assertEquals(404, $response->status());
    }

    public function testUpdateNotExisting() {
        $response = $this->call('PUT', 'api/user/1', ['login' => 'Dawid2']);
        $this->assertEquals(404, $response->status());
    }

    public function testFullUpdate() {
        $this->post('api/user', ['login' => 'Dawid', 'password' => 'test', 'email' => 'test@domain.com']);
        $this->put('api/user/1', ['login' => 'Dawid2', 'password' => 'test2', 'email' => 'test2@domain.com']);
        $this->get('api/user/1')->seeJsonEquals(['login' => 'Dawid2', 'password' => md5('test2'), 'email' => 'test2@domain.com', 'id' => 1]);
    }   

    public function testPartialUpdate() {
        $this->post('api/user', ['login' => 'Dawid', 'password' => 'test', 'email' => 'test@domain.com']);
        $this->put('api/user/1', ['email' => 'test2@domain.com']);
        $this->get('api/user/1')->seeJsonEquals(['login' => 'Dawid', 'password' => md5('test'), 'email' => 'test2@domain.com', 'id' => 1]);
    }  
}
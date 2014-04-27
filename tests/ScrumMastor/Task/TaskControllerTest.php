<?php

namespace tests\ScrumMastor\Task;

use tests\WebTestCaseTest;

class TaskControllerTest extends WebTestCaseTest
{
    public function testSave()
    {
        $this->assertEquals(true, true);
    }

    public function testDeleteEmptyParameter()
    {
        $client = $this->createClient();
        $client->request("DELETE", "/task/");
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testDeleteInvalidParameter()
    {
        $client = $this->createClient();
        $client->request("DELETE", "/task/random", array('id' => 'random'));
        $this->assertEquals(500, $client->getResponse()->getStatusCode());
        $this->assertEquals('{"error":"ID Parameter is invalid"}', $client->getResponse()->getContent());
    }

    public function testDeleteUnknowId()
    {
        $client = $this->createClient();
        $client->request("DELETE", "/task/530f79b2d58c90be0a0041a7", array('id' => '530f79b2d58c90be0a0041a7'));
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals('{"error":"Task not found"}', $client->getResponse()->getContent());
    }

    public function testDeleteFixture()
    {
        $client = $this->createClient();
        $client->request('PUT', '/task/', array('title' => 'Test unit', 'description' => 'Task use in test unit'));
        $this->assertEquals($client->getResponse()->getStatusCode(), 200);

        $result = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($result["success"], "Task Added");
    }

    public function testDelete()
    {
        $client = $this->createClient();
        $tasks = $this->app['mongo']->tasks->find(array('title'=>'Test unit'));
        $tasks = iterator_to_array($tasks);
        foreach ($tasks as $key => $value) {
            $client->request("DELETE", "/task/".$key, array('id' => $key));
            $this->assertEquals(204, $client->getResponse()->getStatusCode());
            $this->assertEquals(null, $client->getResponse()->getContent());
        }
    }

    public function testUpdateEmptyParameter()
    {
        $client = $this->createClient();
        $client->request("PUT", "/task/");
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testUpdateInvalidIdParameter()
    {
        $client = $this->createClient();
        $client->request("PUT", "/task/random", array('id' => 'random', 'title' => 'Test unit has been updated'));
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals('{"error":"Task not found"}', $client->getResponse()->getContent());
    }

    public function testUpdateEmptyTitleDescription()
    {
        $client = $this->createClient();
        $client->request("PUT", "/task/530f79b2d58c90be0a0041a7", array('id' => '530f79b2d58c90be0a0041a7'));
        $this->assertEquals(406, $client->getResponse()->getStatusCode());
        $this->assertEquals('{"error":"Title or Description fields cannot be null"}', $client->getResponse()->getContent());
    }

    public function testUpdateUnknowId()
    {
        $client = $this->createClient();
        $client->request("PUT", "/task/530f79b2d58c90be0a0041a7", array('id' => '530f79b2d58c90be0a0041a7' , 'title' => 'Test unit has been updated'));
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals('{"error":"Task not found"}', $client->getResponse()->getContent());
    }

    public function testUpdateFixture()
    {
        $client = $this->createClient();
        $client->request('PUT', '/task/', array('title' => 'Test unit update', 'description' => 'Task use in test unit'));
        $this->assertEquals($client->getResponse()->getStatusCode(), 200);
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('Task Added', $response['success']);
    }

    public function testUpdateDesc()
    {
        $client = $this->createClient();
        $tasks = $this->app['mongo']->tasks->find(array('title'=>'Test unit update'));
        $tasks = iterator_to_array($tasks);
        foreach ($tasks as $key => $value) {
            $client->request("PUT", "/task/".$key, array('id' => $key, 'description' => 'Test unit has been updated'));
            $this->assertEquals(200, $client->getResponse()->getStatusCode());
            $this->assertEquals('{"success":"Task updated"}', $client->getResponse()->getContent());
        }
    }

    public function testUpdate()
    {
        $client = $this->createClient();
        $tasks = $this->app['mongo']->tasks->find(array('title'=>'Test unit update'));
        $tasks = iterator_to_array($tasks);
        foreach ($tasks as $key => $value) {
            $client->request("PUT", "/task/".$key, array('id' => $key, 'title' => 'Test unit has been updated'));
            $this->assertEquals(200, $client->getResponse()->getStatusCode());
            $this->assertEquals('{"success":"Task updated"}', $client->getResponse()->getContent());
        }
    }
    
    public function testGetTaskFixture()
    {
        $client = $this->createClient();
        $client->request('PUT', '/task/', array('title' => 'Test unit get', 'description' => 'Task use in test unit get'));
        $this->assertEquals($client->getResponse()->getStatusCode(), 200);
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('Task Added', $data['success']);
    }
    
    public function testGetTask()
    {
        $client = $this->createClient();
        $tasks = $this->app['mongo']->tasks->find(array('title'=>'Test unit get'));
        $tasks = iterator_to_array($tasks);
        foreach ($tasks as $key => $value) {
            $client->request("GET", "/task/".$key, array('id' => $key));
            $this->assertEquals(200, $client->getResponse()->getStatusCode());
            $this->assertEquals('[{"title":"Test unit get","description":"Task use in test unit get"}]', $client->getResponse()->getContent());
        }
    }

    public function testGetTasks()
    {
        $client = $this->createClient();
        $tasks = $this->app['mongo']->tasks->find();
        $tasks = iterator_to_array($tasks);
        foreach ($tasks as $key => $value) {
            $client->request("GET", "/task");
            $this->assertEquals(200, $client->getResponse()->getStatusCode());
            $this->assertEquals('[{"title":"Test unit get","description":"Task use in test unit get"}]', $client->getResponse()->getContent());
        }
    }
    
    public function testGetTaskUnknowId()
    {
        $client = $this->createClient();
        $tasks = $this->app['mongo']->tasks->find(array('title'=>'Test unit get'));
        
        $client->request("GET", "/task/530f79b2d58c90be0a0041a7", array('id' => '530f79b2d58c90be0a0041a7'));
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals("\"Task not found\"", $client->getResponse()->getContent());
    }
}

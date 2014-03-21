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
        $this->assertEquals("\"ID Parameter is invalid\"", $client->getResponse()->getContent());
    }

    public function testDeleteUnknowId()
    {
        $client = $this->createClient();
        $client->request("DELETE", "/task/530f79b2d58c90be0a0041a7", array('id' => '530f79b2d58c90be0a0041a7'));
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals("\"Task not found\"", $client->getResponse()->getContent());
    }

    public function testDeleteFixture()
    {
        $client = $this->createClient();
        $client->request('POST', '/task', array('title' => 'Test unit', 'description' => 'Task use in test unit'));
        $this->assertEquals($client->getResponse()->getStatusCode(), 200);

        $result = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($result["success"], "Task Added");
        $this->assertArrayHasKey('_id', $result);
    }

    public function testDelete()
    {
        $client = $this->createClient();
        $tasks = $this->app['mongo']->tasks->find(array('title'=>'Test unit'));
        $tasks = iterator_to_array($tasks);
        foreach ($tasks as $key=>$value) {
            $client->request("DELETE", "/task/".$key, array('id' => $key));
            $this->assertEquals(204, $client->getResponse()->getStatusCode());
            $this->assertEquals(null, $client->getResponse()->getContent());
        }
    }
}

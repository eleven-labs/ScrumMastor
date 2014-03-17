<?php

namespace tests\ScrumMastor\Task;

class TaskControllerTest extends \Silex\WebTestCase
{
    public function createApplication()
    {
        return require __DIR__ . '/../../../web/index_test.php';
    }

    public function testSave()
    {
        $this->assertEquals(true, true);
    }

    public function testDeleteEmptyParameter()
    {
        $client = $this->createClient();
        $client->request("DELETE", "/task");
        $this->assertEquals(500, $client->getResponse()->getStatusCode());
        $this->assertEquals("\"ID Parameter is empty\"", $client->getResponse()->getContent());
    }

    public function testDeleteInvalidParameter()
    {
        $client = $this->createClient();
        $client->request("DELETE", "/task", array('id'=>'random'));
        $this->assertEquals(500, $client->getResponse()->getStatusCode());
        $this->assertEquals("\"ID Parameter is invalid\"", $client->getResponse()->getContent());
    }

    public function testDeleteUnknowId()
    {
        $client = $this->createClient();
        $client->request("DELETE", "/task", array('id'=>'530f79b2d58c90be0a0041a7'));
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals("\"Task not found\"", $client->getResponse()->getContent());
    }

    public function testDeleteFixture()
    {
        $client = $this->createClient();
        $client->request('POST', '/task', array('title' => 'Test unit', 'description' => 'Task use in test unit'));
        $this->assertEquals($client->getResponse()->getStatusCode(), 200);
        $this->assertEquals($client->getResponse()->getContent(), "\"true\"");
    }

    public function testDelete()
    {
        $client = $this->createClient();
        $tasks = $this->app['mongo']->tasks->find(array('title'=>'Test unit'));
        $tasks = iterator_to_array($tasks);
        foreach ($tasks as $key=>$value) {
            $client->request("DELETE", "/task", array('id'=>  $key));
            $this->assertEquals(200, $client->getResponse()->getStatusCode());
            $this->assertEquals("\"true\"", $client->getResponse()->getContent());
        }
    }
}

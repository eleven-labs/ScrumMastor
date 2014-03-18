<?php

namespace tests\ScrumMastor\Task;

use tests\WebTestCaseTest;

class TaskServiceTest extends WebTestCaseTest
{
    public function testIsValidId()
    {
        $taskService = new \ScrumMastor\Service\TaskService($this->app['mongo']);
        $return = $taskService->isValidId("530f79b2d58c90be0a0041a0");
        $this->assertTrue($return);
    }

    public function testInvalidId()
    {
        $taskService = new \ScrumMastor\Service\TaskService($this->app['mongo']);
        $return = $taskService->isValidId("azer");
        $this->assertFalse($return);
    }

    public function testEmpty()
    {
        $taskService = new \ScrumMastor\Service\TaskService($this->app['mongo']);
        $return = $taskService->isValidId("");
        $this->assertFalse($return);
    }

    public function testNotExistId()
    {
        $taskService = new \ScrumMastor\Service\TaskService($this->app['mongo']);
        $return = $taskService->existId("530f79b2d58c90be0a0041a0");
        $this->assertFalse($return);
    }

    public function testExistId()
    {
        $data = array('title' => 'Test service as unit', 'description' => 'I can haz cheezburger');
        $this->app['mongo']->tasks->insert($data);

        $taskService = new \ScrumMastor\Service\TaskService($this->app['mongo']);
        $return = $taskService->existId($data['_id']);
        $this->assertTrue($return);
    }

    public function testExistInvalidId()
    {
        $taskService = new \ScrumMastor\Service\TaskService($this->app['mongo']);
        $return = $taskService->existId("azer");
        $this->assertFalse($return);
    }
}

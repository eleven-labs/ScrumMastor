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
    
    public function testInsertTask(){
        $taskService = new \ScrumMastor\Service\TaskService($this->app['mongo']);
        $return = $taskService->insertTask(array('title' => 'Test unit insert', 'description' => 'Test unit insert description'));
        $this->assertTrue($return);
    }
    
    public function testInsertTaskEmpty(){
        $taskService = new \ScrumMastor\Service\TaskService($this->app['mongo']);
        $return = $taskService->insertTask(array());
        $this->assertFalse($return);
    }
    
    public function testInsertTaskString(){
        $taskService = new \ScrumMastor\Service\TaskService($this->app['mongo']);
        $return = $taskService->insertTask('stagea');
        $this->assertFalse($return);
    }
    
    public function testRemoveTask(){
        $data = array('title' => 'Test service as unit', 'description' => 'I can haz cheezburger');
        $this->app['mongo']->tasks->insert($data);
        
        $taskService = new \ScrumMastor\Service\TaskService($this->app['mongo']);
        $return = $taskService->removeTask(array('title' => 'Test service as unit'));
        $this->assertTrue($return);
    }
    
    public function testRemoveTaskAll(){
        $taskService = new \ScrumMastor\Service\TaskService($this->app['mongo']);
        $return = $taskService->removeTask(array());
        $this->assertTrue($return);
    }
    
    public function testRemoveTaskString(){
        $taskService = new \ScrumMastor\Service\TaskService($this->app['mongo']);
        $return = $taskService->removeTask('stagea');
        $this->assertFalse($return);
    }
    
    public function testUpdateTask(){
        $data = array('title' => 'Test service as unit', 'description' => 'I can haz cheezburger');
        $this->app['mongo']->tasks->insert($data);
        
        $taskService = new \ScrumMastor\Service\TaskService($this->app['mongo']);
        $return = $taskService->updateTask(array('_id' => $data['_id']),
                array('$set' => array('title' => 'egaegaeg')));
        $this->assertTrue($return);
    }
}

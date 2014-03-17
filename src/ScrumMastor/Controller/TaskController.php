<?php

namespace ScrumMastor\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController
{
	protected $mongo;
	protected $request;
	
	public function __construct($mongo, Request $request)
	{
		$this->mongo = $mongo;
		$this->request = $request;
	}

	public function saveAction()
	{
		$title = $this->request->get('title');
		if (!isset($title)){
			return new JsonResponse('false', 500);
		}
		
		$this->mongo->tasks->insert(array('title' =>  $title, 'description' =>  $this->request->get('description', '')));

		return new JsonResponse('true', 200);
	}
    
    /**
     * Delete task by ID
     * @return JsonResponse String and HTTP Code
     */
    public function deleteAction()
    {
        $id = $this->request->get('id');
        
        if(empty($id)){
            return new JsonResponse("ID Parameter is empty", 500); //input invalid
        }
        
        try {
            $mongoId = new \MongoId($id);
        } catch (\MongoException $e) {
            return new JsonResponse("ID Parameter is invalid", 500); //input invalid
        }
        
        $task = $this->mongo->tasks->find(array('_id' => new \MongoId($id)), array('_id'=>true));

        if(!count(iterator_to_array($task))){
            return new JsonResponse("Task not found", 404); //id not found
        }
        
        $return = $this->mongo->tasks->remove(array('_id' => new \MongoId($id)));
        if(is_null($return['err'])){
            return new JsonResponse('true', 200); //delete with success
        }else{
            return new JsonResponse('false', 500); //cannot remove
        }
    }

}

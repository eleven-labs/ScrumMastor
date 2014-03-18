<?php

namespace ScrumMastor\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TaskController
{
    protected $mongo;
    protected $request;
    protected $taskService;

    public function __construct($mongo, Request $request, $taskService)
    {
        $this->mongo = $mongo;
        $this->request = $request;
        $this->taskService = $taskService;
    }

    public function saveAction()
    {
        $title = $this->request->get('title');
        if (!isset($title)) {
            return new JsonResponse('false', 500);
        }

        $task = array('title' =>  $title, 'description' =>  $this->request->get('description', ''));

        $this->mongo->tasks->insert($task);

        return new JsonResponse(["success" => "Task Added", "_id" => $task["_id"]], 200);
    }

    /**
     * Delete task by ID
     * @param  string       $id ID of Task
     * @return JsonResponse String and HTTP Code
     */
    public function deleteAction($id)
    {
        $id = $this->request->get('id');

        if (empty($id)) {
            return new JsonResponse("ID Parameter is empty", 500); //input invalid
        }

        try {
            $mongoId = new \MongoId($id);
        } catch (\MongoException $e) {
            return new JsonResponse("ID Parameter is invalid", 500); //input invalid
        }

        $task = $this->mongo->tasks->find(array('_id' => new \MongoId($id)), array('_id' => true));

        if ($task->count() === 0) {
            return new JsonResponse("Task not found", 404); //id not found
        }

        $return = $this->mongo->tasks->remove(array('_id' => new \MongoId($id)));
        if (is_null($return['err'])) {
            return new JsonResponse(null, 204); //delete with success
        } else {
            return new JsonResponse('Cannot remove task', 401); //cannot remove
        }
    }

    /**
     * Edit task by ID
     * @param  string       $id ID of Task
     * @return JsonResponse String and HTTP Code
     */
    public function updateAction($id)
    {
        $title = $this->request->get('title');
        $description = $this->request->get('description');

        if (empty($title) && empty($description)) {
            return new JsonResponse("Title or Description fields cannot be null", 406);
        }

        if ($this->taskService->existId($id)) {
            $return = $this->mongo->tasks->update(
                array('_id' => new \MongoId($id)),
                array('$set' => array('title' => 'egaegaeg'))
            );

            if ($return['err'] === null) {
                return new JsonResponse("Task updated", 200);
            } else {
                return new JsonResponse("Cannot update task", 401);
            }
        } else {
            return new JsonResponse("Task not found", 404);
        }
    }

}

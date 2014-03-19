<?php

namespace ScrumMastor\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TaskController
{
    protected $mongo;
    protected $request;
    protected $taskService;

    public function __construct(Request $request, $taskService)
    {
        $this->request = $request;
        $this->taskService = $taskService;
    }

    public function saveAction()
    {
        $title = $this->request->get('title');
        if (!isset($title)) {
            return new JsonResponse('false', 500);
        }

        $data = array('title' => $title, 'description' => $this->request->get('description', ''));
        $return = $this->taskService->insertTask($data);
        if ($return) {
            return new JsonResponse('true', 200);
        } else {
            return new JsonResponse('Cannot insert Task', 500);
        }
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

        if (!$this->taskService->isValidId($id)) {
            return new JsonResponse("ID Parameter is invalid", 500);
        }

        if ($this->taskService->existId($id)) {
            $return = $this->taskService->removeTask(array('_id' => new \MongoId($id)));
            if ($return) {
                return new JsonResponse(null, 204); //delete with success
            } else {
                return new JsonResponse('Cannot remove task', 401); //cannot remove
            }
        } else {
            return new JsonResponse("Task not found", 404);
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
        $newData = array();
        if (empty($title) && empty($description)) {
            return new JsonResponse("Title or Description fields cannot be null", 406);
        }
        
        if(!empty($title)){
            $newData['title'] = $title;
        }
        
        if(!empty($description)){
            $newData['description'] = $description;
        }

        if ($this->taskService->existId($id)) {
            $return = $this->taskService->updateTask(
                array('_id' => new \MongoId($id)),
                array('$set' => $newData)
            );

            if ($return) {
                return new JsonResponse("Task updated", 200);
            } else {
                return new JsonResponse("Cannot update task", 401);
            }
        } else {
            return new JsonResponse("Task not found", 404);
        }
    }

}

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

    /**
     * Insert task
     * @return JsonResponse    Return a JsonResponse and HTTP Code
     *
     * @ApiDescription(section="Task", description="Insert task. Return a 'success' and 200 HTTP Code, or 'error' and 500 HTTP Code")
     * @ApiMethod(type="post")
     * @ApiRoute(name="/task")
     * @ApiParams(name="title", type="string", nullable=false, description="Title of taks")
     * @ApiParams(name="description", type="string", nullable=true, description="Description of task")
     * @ApiReturn(type="object", sample="Status Code : 200<br>{'success' : 'Task successfullly added'}")
     * @ApiReturn(type="object", sample="Status Code : 500<br>{'error' : 'Cannot insert task'}")
     */
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
     *
     * @ApiDescription(section="Task", description="Delete task by ID")
     * @ApiMethod(type="delete")
     * @ApiRoute(name="/task/{id}")
     * @ApiParams(name="id", type="string", nullable=false, description="ID of task")
     * @ApiReturn(type="object", sample="Status Code : 500<br>{'error' : 'ID Parameter is empty'}")
     * @ApiReturn(type="object", sample="Status Code : 204<br>{'success' : 'Task successfullly deleted'}")
     * @ApiReturn(type="object", sample="Status Code : 401<br>{'error' : 'Cannot remove task'}")
     * @ApiReturn(type="object", sample="Status Code : 404<br>{'error' : 'Task not found'}")
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
     *
     * @ApiDescription(section="Task", description="Update task by ID")
     * @ApiMethod(type="put")
     * @ApiRoute(name="/task/{id}")
     * @ApiParams(name="id", type="string", nullable=false, description="ID of task to update")
     * @ApiParams(name="title", type="string", nullable=true, description="New title")
     * @ApiParams(name="description", type="string", nullable=true, description="New description")
     * @ApiReturn(type="object", sample="Status Code : 406<br>{'error' : 'Title or Description fields cannot be null'}")
     * @ApiReturn(type="object", sample="Status Code : 200<br>{'success' : 'Task successfullly updated'}")
     * @ApiReturn(type="object", sample="Status Code : 401<br>{'error' : 'Cannot update task'}")
     * @ApiReturn(type="object", sample="Status Code : 404<br>{'error' : 'Task not found'}")
     */
    public function updateAction($id)
    {
        $title = $this->request->get('title');
        $description = $this->request->get('description');
        $newData = array();
        if (empty($title) && empty($description)) {
            return new JsonResponse("Title or Description fields cannot be null", 406);
        }

        if (!empty($title)) {
            $newData['title'] = $title;
        }

        if (!empty($description)) {
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

    /**
     * Get task by ID
     * @param  string       $id ID of Task
     * @return JsonResponse String and HTTP Code
     *
     * @ApiDescription(section="Task", description="Get task by ID")
     * @ApiMethod(type="get")
     * @ApiRoute(name="/task/{id}")
     * @ApiParams(name="id", type="string", nullable=false, description="ID of task to get")
     * @ApiReturn(type="object", sample="Status Code : 406<br>{'error' : 'Title or Description fields cannot be null'}")
     * @ApiReturn(type="object", sample="Status Code : 200<br>{'title' : 'HAI', 'description' : 'I can haz cheezburger'}")
     */
    public function getAction($id)
    {
        if ($task = $this->taskService->getTaskById($id, array('title' => true, 'description' => true, '_id' => false))) {
            return new JsonResponse($task, 200);
        } else {
            return new JsonResponse("Task not found", 404);
        }
    }

}

<?php

namespace ScrumMastor\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TagController
{
    protected $mongo;
    protected $request;

    public function __construct($mongo, Request $request)
    {
        $this->mongo = $mongo;
        $this->request = $request;
    }

    /**
     * Return all tags (for listing)
     *
     * @return JsonResponse    Return a JsonResponse and HTTP Code
     *
     * @ApiDescription(section="Tag", description="Return all tags (for listing)")
     * @ApiMethod(type="get")
     * @ApiRoute(name="/tags")
     * @ApiReturn(type="object", sample="Return collection of tag")
     */
    public function listAction()
    {
        $tags = $this->mongo->tags->find(array(), array("name" => true, "_id" => false));

        return new JsonResponse(iterator_to_array($tags), 200);
    }

    /**
     * Add tag to the tag list
     * @return JsonResponse    Return a JsonResponse and HTTP Code
     *
     * @ApiDescription(section="Tag", description="Add tag to the tag list")
     * @ApiMethod(type="post")
     * @ApiRoute(name="/tag")
     * @ApiParams(name="name", type="string", nullable=false, description="Name of tag")
     * @ApiReturn(type="object", sample="Status Code : 500<br>{'error' : 'Name parameter is required'}")
     * @ApiReturn(type="object", sample="Status Code : 200<br>{'success' : 'Tag successfully added'}")
     */
    public function saveAction()
    {
        $name = $this->request->get('name');

        if (!isset($name)) {
            return new JsonResponse('false', 500);
        }

        $this->mongo->tags->insert(array('name' =>  $name));

        return new JsonResponse('true', 200);
    }

    /**
    *
    * Delete tag in tag list
    * @return JsonResponse    Return a JsonResponse and HTTP Code
    *
    * @ApiDescription(section="Tag", description="Delete tag in tag list")
    * @ApiMethod(type="delete")
    * @ApiRoute(name="/tag")
    * @ApiParams(name="name", type="string", nullable=false, description="Name of tag")
    * @ApiReturn(type="object", sample="Status Code : 500<br>{'error' : 'Name parameter is required'}")
    * @ApiReturn(type="object", sample="Status Code : 200<br>{'success' : 'Tag successfully added'}")
    */
    public function deleteAction()
    {
        $name = $this->request->get('name');

        if (!isset($name)) {
            return new JsonResponse('false', 500);
        }

        $this->mongo->tags->remove(array('name' =>  $name));

        return new JsonResponse('true', 200);
    }

    /**
     * Add tag to a task
     * @return JsonResponse    Return a JsonResponse and HTTP Code
     *
     * @ApiDescription(section="Tag", description="Add tag to a task")
     * @ApiMethod(type="post")
     * @ApiRoute(name="/addTag")
     * @ApiParams(name="name", type="string", nullable=false, description="Name of tag")
     * @ApiParams(name="task", type="string", nullable=false, description="ID of task")
     * @ApiReturn(type="object", sample="Status Code : 500<br>{'error' : 'Name and ID Task parameters are required'}")
     * @ApiReturn(type="object", sample="Status Code : 404<br>{'error' : 'Task not found'}")
     * @ApiReturn(type="object", sample="Status Code : 200<br>{'success' : 'Tag successfully added'}")
     */
    public function setTagAction()
    {
        $name = $this->request->get('name');
        $id = $this->request->get('task');

        if (!$name || !$id) {
            return new JsonResponse('false', 500);
        }

        $task = $this->mongo->tasks->findOne(array('_id' => new \MongoId($id)));

        if (!$task) {
            return new JsonResponse(["error" => "Task not found"], 404);
        }

        $this->mongo->tasks->update(array('_id' => new \MongoId($id)), array('$addToSet' => array('Tags' => $name)));
        $return = ["sucess" => "Tag Added"];

        return new JsonResponse($return, 200);

    }

    /**
    *
    * Search tasks with tag
    * @return JsonResponse    Return a JsonResponse and HTTP Code
    *
    * @ApiDescription(section="Tag", description="Search tasks with tag")
    * @ApiMethod(type="get")
    * @ApiRoute(name="/search/tag")
    * @ApiParams(name="name", type="string", nullable=false, description="Name of tag")
    * @ApiReturn(type="object", sample="Status Code : 500<br>{'error' : 'Name parameter is required'}")
    * @ApiReturn(type="object", sample="Status Code : 200<br>{'success' : 'Tag successfully added'}")
    */
    public function searchAction()
    {
        $name = $this->request->get('name');

        if (!isset($name) || $name == '') {
            return new JsonResponse('false', 500);
        }

        $tasks = $this->mongo->tasks->find(array("Tags" => $name));

        return new JsonResponse(iterator_to_array($tasks), 200);

    }

}

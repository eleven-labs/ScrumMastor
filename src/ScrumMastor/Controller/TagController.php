<?php

namespace ScrumMastor\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
    *
    * Return all tags (for listing)
    */
    public function listAction() {

        $tags = $this->mongo->tags->find(array(), array("name" => true, "_id" => false));

        return new JsonResponse(iterator_to_array($tags), 200);
    }


    /**
    *
    * Add tag to the tag list
    *
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
    * Add tag to a task
    *
    */
    public function setTagAction() {
        $name = $this->request->get('name');
        $id = $this->request->get('task');

        if (!isset($name) || !isset($id)) {
            return new JsonResponse('false', 500);
        }

        $task = $this->mongo->tasks->findOne(array('_id' => new \MongoId('530f79b2d58c90be0a0041a7')));

        if (!$task) {
            return new JsonResponse('Task not found', 404);
        }

        $this->mongo->tasks->update(array('_id' => new \MongoId('530f79b2d58c90be0a0041a7')), array('$addToSet' => array('Tags' => $name)));

        return new JsonResponse('true', 200);

    }




}
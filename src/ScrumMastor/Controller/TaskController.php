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
		if (!isset($this->request->get('title'))) {
			return new JsonResponse('', 500);
		}

		$this->mongo->tasks->insert({'title': $this->request->get('title'), ''})

		return new JsonResponse('', 200);
	}

    public function deleteAction($id)
    {
        $this->mongo->tasks->delete({'id': $id}, {"justOne": true});

        return new JsonResponse('', 200);
    }
}

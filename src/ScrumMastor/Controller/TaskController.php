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

}

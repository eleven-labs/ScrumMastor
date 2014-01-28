<?php

namespace ScrumMastor\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

class DemoController
{
    public function indexAction()
    {
        return $app['twig']->render('demo.twig');
    }
}
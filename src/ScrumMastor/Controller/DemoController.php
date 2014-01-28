<?php

namespace ScrumMastor\Controller;

class DemoController
{
    protected $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function indexAction()
    {
        return $this->twig->render('demo.twig');
    }
}

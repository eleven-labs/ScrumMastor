<?php

namespace ScrumMastor\Provider;

use Silex\ServiceProviderInterface;
use ScrumMastor\Controller\DemoController;
use ScrumMastor\ScrumMastorApplication;
use Silex\Application;

class ScrumMastorProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
    	// Here yes can add your service
    	// $app['name.service'] = $this->share(function ($app) {
		//    return new MyService();
    	// });
    	$app['demo.controller'] = $app->share(function ($app) {
		    return new DemoController($app['twig']);
		});
    }

    public function boot(Application $app)
    {
        // Nothin todo
    }
}

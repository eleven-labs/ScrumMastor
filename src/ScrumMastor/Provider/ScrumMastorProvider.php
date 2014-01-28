<?php

namespace ScrumMastor\Provider;

use ScrumMastor\Controller\DemoController;
use ScrumMastor\ScrumMastorApplication;

class ScrumMastorProvider implements ServiceProviderInterface
{
    public function register(ScrumMastorApplication $app)
    {
    	// Here yes can add your service
    	// $app['name.service'] = $this->share(function ($app) {
		//    return new MyService();
    	// });

    	$app['demo.controller'] = $app->share(function ($app) {
		    return new DemoController();
		});
    }

    public function boot(ScrumMastorApplication $app)
    {
        // Nothin todo
    }
}

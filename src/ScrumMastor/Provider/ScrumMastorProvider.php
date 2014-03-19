<?php

namespace ScrumMastor\Provider;

use Silex\ServiceProviderInterface;
use ScrumMastor\Controller\TaskController;
use ScrumMastor\Controller\TagController;
use ScrumMastor\Service\TaskService;
use Silex\Application;

class ScrumMastorProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        // Here yes can add your service
        // $app['name.service'] = $this->share(function ($app) {
        //    return new MyService();
        // });
        $app['task.controller'] = $app->share(function ($app) {
            return new TaskController($app['request'], $app['task.service']);
        });

        $app['tag.controller'] = $app->share(function ($app) {
            return new TagController($app['mongo'], $app['request']);
        });

        $app['task.service'] = $app->share(function ($app) {
            return new TaskService($app['mongo']);
        });
    }

    public function boot(Application $app)
    {
        // Nothin todo
    }
}

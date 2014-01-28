<?php 

namespace ScrumMastor;

use Silex\Application as SilexApplication;

class ScrumMastorApplication extends SilexApplication
{
	/**
     * Constructor.
     *
     * Register default services.
     */
    public function __construct(array $config)
    {
        parent::__construct($config);

        $baseDir = $this->getBaseDir();

        // Default configuration
        $this['app.name']       = 'ScrumMastor';
        $this['profiler']       = false;
        $this['monolog.level']  = Logger::DEBUG;
        $this['path.config']    = $baseDir.'/config';
        $this['path.cache']     = $baseDir.'/cache';
        $this['path.log']       = $baseDir.'/log';
        $this['path.views']     = $baseDir.'/views';
        $this['path.web']       = $baseDir.'/web';
        $this['path.data']      = $baseDir.'/data';

        // register monolog
        $this['monolog'] = $this->share(function ($app) {
            $log = new Logger($app['app.name']);

            if ($app['debug']) {
                $log->pushProcessor(new MemoryUsageProcessor());
                $log->pushProcessor(new MemoryPeakUsageProcessor());
            }

            $handler = new StreamHandler($app['path.log'].'/app.log', $app['monolog.level']);
            if (!$app['debug']) {
                $handler = new FingersCrossedHandler($handler, Logger::ERROR);
            }
            $log->pushHandler($handler);

            if ($app['debug'] || $app['profiler']) {
                $log->pushHandler(new DebugHandler($app['monolog.level']));
            }

            return $log;
        });

        $this['logger'] = function ($app) {
            return $app['monolog'];
        };

        // register twig
        $this->register(new Provider\TwigServiceProvider());
        $this['twig.path'] = $this['path.views'];
        $this['twig.options'] = function ($app) {
            return array(
                'cache'     => $app['path.cache'] . '/twig',
                'charset'   => $app['charset'],
            );
        };

        $this->register(new Silex\Provider\ServiceControllerServiceProvider());
        $this->register(new ScrumMastor\ScrumMastorProvider());

        // load config
        $this->loadConfig();
        $this->loadRoute();
    }

    public function boot()
    {
        $app = $this;

        if (!isset($this['app.name'])) {
            throw new \LogicException('You must define "app.name" configuration !');
        }

        // Log
        $this->before(function (Request $request) use ($app) {
            $app['monolog']->addInfo('> '.$request->getMethod().' '.$request->getRequestUri());
        });

        $this->error(function (\Exception $e) use ($app) {
            $app['monolog']->addError($e->getMessage(), array('exception' => $e));
        }, 255);

        $this->after(function (Request $request, Response $response) use ($app) {
            $app['monolog']->addInfo('< '.$response->getStatusCode());
        });

        parent::boot();
    }

    /**
     * Get the application base directory from the location of the Application class.
     *
     * @return string
     */
    protected function getBaseDir()
    {
        $ref = new \ReflectionObject($this);

        return realpath(dirname(dirname($ref->getFileName())));
    }

    protected function loadConfig()
    {
        $config = array_replace_recursive(
            require $this['path.config'] . '/default.php',
            require $this['path.config'] . '/' . $this['env'] . '.php'
        );

        foreach ($config as $key => $value) {
            $this[$key] = $value;
        }
    }

    protected function loadRoute()
    {
    	$this->get('/', 'demo.controller:index');
    }
}

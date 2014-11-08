<?php
Namespace Api;

use Silex\Application;
use Silex\Provider\MonologServiceProvider;
use Flint\Provider\ConfigServiceProvider;
use Flint\Provider\RoutingServiceProvider;

class App extends Application
{
	use Application\MonologTrait;

    public function __construct(array $values = array())
    {
        parent::__construct($values);

		$this->register(new MonologServiceProvider(), array(
		    'monolog.name'    => 'api',
		    'monolog.logfile' => __DIR__ . '/../../runtime/log/app.log',
		    'monolog.level'   => 100 // = Logger::DEBUG
		));

		$this["root_dir"] = __DIR__;

		$this->register(new \Silex\Provider\UrlGeneratorServiceProvider());

		$this->register(new \Flint\Provider\ConfigServiceProvider);
		$this->register(new \Flint\Provider\RoutingServiceProvider, array(
		    'routing.resource' => __DIR__ . '/../../config/routes.yml',
		    'routing.options' => array(
		        'cache_dir' => __DIR__ . '/../../runtime/cache',
		    ),
		));

		$app = $this;

		$this['resolver'] = $this->share(function () use ($app) {
    		return new \Api\Controller\ControllerResolver($this);
		});
	}
}
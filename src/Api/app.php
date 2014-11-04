<?php
Namespace Api;

use Silex\Application;
use Silex\Provider\MonologServiceProvider;

class App extends Application
{
	use Application\MonologTrait;

    public function __construct(array $values = array())
    {
        parent::__construct($values);

		$this->register(new MonologServiceProvider(), array(
		    'monolog.name'    => 'api',
		    'monolog.logfile' => __DIR__ . '/../../log/app.log',
		    'monolog.level'   => 100 // = Logger::DEBUG
		));
	}
}
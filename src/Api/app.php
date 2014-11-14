<?php
Namespace Api;

use Silex\Application;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Flint\Provider\ConfigServiceProvider;
use Flint\Provider\RoutingServiceProvider;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class App extends Application
{
	use Application\MonologTrait;

    public function __construct(array $values = array())
    {
        parent::__construct($values);

        $this->registerServices();
        $this->setupMiddleware();
    }

    protected function registerServices()
    {
    	// Monolog
		$this->register(new MonologServiceProvider(), array(
		    'monolog.name'    => 'api',
		    'monolog.logfile' => __DIR__ . '/../../runtime/log/app.log',
		    'monolog.level'   => 100 // = Logger::DEBUG
		));

		// UrlGenerator
		$this["root_dir"] = __DIR__;
		$this->register(new \Silex\Provider\UrlGeneratorServiceProvider());

		// Routing
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

		// Doctrine DBAL
		$this->register(new \Silex\Provider\DoctrineServiceProvider(), array(
		    'db.options' => array(
		        'driver'   => 'pdo_sqlite',
		        'path'     => __DIR__ . '/../../runtime/app.db',
		    ),
		));

		// Doctrine ORM
		$app->register(new \Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider(), array(
		    'orm.em.options' => [
		        'mappings' => [
		            [
		                'type' => 'annotation',
		                'namespace' => 'Api\Model',
		                'path' => __DIR__ . '/Model',
		                'use_simple_annotation_reader' => false,
		            ]
		        ],
		    ]
		));
	}

	protected function setupMiddleware()
    {
        // $this->before(function (Request $request) {
        //     if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        //         $data = Json::decode($request->getContent(), true);
        //         $request->request->replace(is_array($data) ? $data : array());
        //     }
        // });

        $this->error(function (HttpException $e, $code) {
            return new JsonResponse(['error' => ['message' => $e->getMessage()]], $e->getStatusCode());
        });

        $this->error(function (\Exception $e, $code) {
            $message = ($this['debug']) ? (string) $e : 'Internal error';
            $this['monolog']->addError((string) $e);

            return new JsonResponse(['error' => ['message' => $message]], 500);
        });
    }
}
<?php
namespace Api\Controller;

use Silex\ControllerResolver as SilexControllerResolver;

class ControllerResolver extends SilexControllerResolver
{
    /**
     * Returns a callable for the given controller.
     *
     * @param string $controller A Controller string
     *
     * @return mixed A PHP callable
     */
    protected function createController($controller)
    {
        if (false === strpos($controller, '::')) {
            throw new \InvalidArgumentException(sprintf('Unable to find controller "%s".', $controller));
        }

        list($class, $method) = explode('::', $controller, 2);

        $class = sprintf('%s\\%s', __NAMESPACE__, $class);

        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
        }

        $controller = new $class();
        if ($controller instanceof AbstractController) {
            $controller->setContainer($this->app);
        }

        return array($controller, $method);
    }
}
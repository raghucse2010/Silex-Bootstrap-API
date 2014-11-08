<?php
namespace Api\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends AbstractController
{
    public function indexAction(Request $request, Application $app)
    {
		return new JsonResponse(array('message' => 'Nothing to be seen here.'));
    }
}
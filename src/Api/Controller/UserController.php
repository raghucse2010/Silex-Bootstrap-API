<?php
namespace Api\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends AbstractController
{
    public function listAction(Request $request, Application $app)
    {
		return new JsonResponse(array());
    }

    public function fetchAction(Request $request, Application $app)
    {
		return new JsonResponse(array());
    }

    public function createAction(Request $request, Application $app)
    {
		return new Response(null, 201);
    }

    public function updateAction(Request $request, Application $app)
    {
		return new Response(null, 204);
    }

    public function deleteAction(Request $request, Application $app)
    {
		return new Response(null, 204);
    }
}
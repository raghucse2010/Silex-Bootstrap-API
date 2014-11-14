<?php
namespace Api\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

use Api\Model\User;
use Api\Transformer\UserTransformer;

class UserController extends AbstractController
{
	/**
	 * Get all the users.
	 *
	 * @param Request $request       Http Request.
	 * @param Application $app       The silex app.
	 *
	 * @return Response
	 */
    public function listAction(Request $request, Application $app)
    {
		$users = $app["orm.em"]->getRepository('Api\Model\User')->findAll();

		$fractal = new Manager();

		$resource = new Collection($users, new UserTransformer);

		return new Response($fractal->createData($resource)->toJson());
    }

	/**
	 * Fetch a specific users by id.
	 *
	 * @param Request $request       Http Request.
	 * @param Application $app       The silex app.
	 *
	 * @return Response
	 */
    public function fetchAction(Request $request, Application $app)
    {
		$user = $app["orm.em"]->getRepository('Api\Model\User')->find($request->get("id"));

		if ($user == null)
			return new Response(null, Response::HTTP_NOT_FOUND, array("Content-Type" => "application/json"));

		$fractal = new Manager;

		$resource = new Item($user, new UserTransformer);

		return new Response($fractal->createData($resource)->toJson());
    }

	/**
	 * Create a new User.
	 *
	 * @param Request $request       Http Request.
	 * @param Application $app       The silex app.
	 *
	 * @return Response
	 */
    public function createAction(Request $request, Application $app)
    {
    	$user = new User($request->get("name"));

    	$user->setEmail($request->get("email"));
    	$user->setPassword($request->get("password"));

    	$app["orm.em"]->persist($user);
		$app["orm.em"]->flush();

		$fractal = new Manager;

		$resource = new Item($user, new UserTransformer);

		return new Response($fractal->createData($resource)->toJson(), Response::HTTP_CREATED);
    }

	/**
	 * Update a User.
	 *
	 * @param Request $request       Http Request.
	 * @param Application $app       The silex app.
	 *
	 * @return Response
	 */
    public function updateAction(Request $request, Application $app)
    {
		$user = $app["orm.em"]->getRepository('Api\Model\User')->find($request->get("id"));

		if ($user == null)
			return new Response(null, Response::HTTP_NOT_FOUND, array("Content-Type" => "application/json"));

		$user->setName($request->get("name"));
		$user->setEmail($request->get("email"));
		$user->setPassword($request->get("password"));

    	$app["orm.em"]->persist($user);
		$app["orm.em"]->flush();

		return new Response(null, Response::HTTP_NO_CONTENT);
    }

	/**
	 * Delete a User.
	 *
	 * @param Request $request       Http Request.
	 * @param Application $app       The silex app.
	 *
	 * @return Response
	 */
    public function deleteAction(Request $request, Application $app)
    {
		$user = $app["orm.em"]->getRepository('Api\Model\User')->find($request->get("id"));

		if ($user == null)
			return new Response(null, Response::HTTP_NOT_FOUND, array("Content-Type" => "application/json"));

		$app["orm.em"]->remove($user);
		$app["orm.em"]->flush();

		return new Response(null, Response::HTTP_NO_CONTENT);
    }
}

<?php
namespace Api\Transformer;

use Api\Model\User;
use League\Fractal;

class UserTransformer extends Fractal\TransformerAbstract
{
	/**
	 * Indicates to Fractal how to transform the User model into a json
	 *
	 * @param User $user       User entity.
	 *
	 * @return array
	 */
	public function transform(User $user)
	{
	    return [
	        'id'     => (int) $user->getId(),
	        'name'   => $user->getName(),
	        'email'  => $user->getEmail()
	    ];
	}
}
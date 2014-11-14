<?php
namespace Api\Transformer;

use Api\Model\User;
use League\Fractal;

class UserTransformer extends Fractal\TransformerAbstract
{
	public function transform(User $user)
	{
	    return [
	        'id'     => (int) $user->getId(),
	        'name'   => $user->getName(),
	        'email'  => $user->getEmail()
	    ];
	}
}
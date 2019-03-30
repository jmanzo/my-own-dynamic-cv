<?php

namespace App\Controllers;

use App\Models\User;
use Respect\Validation\Validator as validator;
use Respect\Validation\Exceptions\NestedValidationException;

class UsersController extends BaseController
{
	public function getAddUser()
	{
		$messageResponse = null;

		return $this->renderHTML('add-user.twig', [
			'messageResponse' => $messageResponse,
		]);
	}

	public function postSaveUser($request)
	{
		$messageResponse = null;

		if ($request->getMethod() == 'POST') {
			$postData = $request->getParsedBody();
			$userValidator = validator::key('fullname', validator::stringType()->notEmpty())
			    ->key('username', validator::stringType()->notEmpty())
			    ->key('email', validator::stringType()->notEmpty())
			    ->key('password', validator::stringType()->notEmpty());

			try {
				$userValidator->assert($postData);

			    $user = new User();
			    $user->full_name = $postData['fullname'];
			    $user->username = $postData['username'];
			    $user->email = $postData['email'];
			    $user->password = password_hash($postData['password'], PASSWORD_DEFAULT);
			    $user->save();
			    $messageResponse = 'User Saved Successfully';
			} catch (NestedValidationException $e) {
				$messageResponse = $e->getMessage();
			}
		}

		return $this->renderHTML('add-user.twig', [
			'messageResponse' => $messageResponse,
		]);
	}
}
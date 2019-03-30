<?php

namespace App\Controllers;

use App\Models\User;
use Respect\Validation\Validator as validator;
use Respect\Validation\Exceptions\NestedValidationException;
use Zend\Diactoros\Response\RedirectResponse;

class AuthController extends BaseController
{
	public function getLogin()
	{
		return $this->renderHTML('login.twig');
	}


	public function getLogout()
	{
		unset($_SESSION['userId']);

		return new RedirectResponse('/login');
	}
	

	public function postAuth($request)
	{
		$responseMessage = null;

		if ($request->getMethod() == 'POST') {
			$postData = $request->getParsedBody();
			$user = User::where('email', $postData['email'])->first();

			if ($user) {
				if (password_verify($postData['password'], $user->password)) {
					$_SESSION['userId'] = $user->id;

					return new RedirectResponse('/admin');
				} else {
					$responseMessage = 'Bad credentials';
				}
			} else {
				$responseMessage = 'Bad credentials';
			}
		}

		return $this->renderHTML('login.twig', [
			'responseMessage' => $responseMessage
		]);
	}
}
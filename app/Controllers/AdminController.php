<?php

namespace App\Controllers;

use Zend\Diactoros\Response\RedirectResponse;

class AdminController extends BaseController
{
	public function getIndex()
	{
		return $this->renderHTML('admin.twig');
	}
}
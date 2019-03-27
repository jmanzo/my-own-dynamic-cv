<?php

namespace App\Controllers;

use App\Models\{Job, Project};

class IndexController extends BaseController
{
	public function indexAction() 
	{
		$name = 'Hector Benitez';
		$limitMonths = 2000;
		$jobs = Job::all();

		return $this->renderHTML('index.twig', [
			'name' => $name,
			'jobs' => $jobs,
		]);
	}
}
<?php

namespace App\Controllers;

use App\Models\{Job, Project};

class IndexController
{
	public function indexAction() 
	{
		$name = 'Hector Benitez';
		$limitMonths = 2000;
		$jobs = Job::all();
		$project1 = new Project('Project 1', 'Description 1');
		$projects = [
		    $project1
		];

		include '../views/index.php';
	}
}
<?php

namespace App\Controllers;

use App\Models\Job;
use Respect\Validation\Validator as validator;
use Respect\Validation\Exceptions\NestedValidationException;

class JobsController extends BaseController
{
	public function getAddJob($request)
	{
		$messageResponse = null;

		if ($request->getMethod() == 'POST') {
			$postData = $request->getParsedBody();
			$jobValidator = validator::key('title', validator::stringType()->notEmpty())
			    ->key('description', validator::stringType()->notEmpty());

			try {
				$jobValidator->assert($postData);

				$files = $request->getUploadedFiles();
				$logo = $files['logo'];

				if ($logo->getError() == UPLOAD_ERR_OK) {
					$fileName = $logo->getClientFilename();
					$logo->moveTo("uploads/$fileName");
				}

			    $job = new Job();
			    $job->title = $postData['title'];
			    $job->description = $postData['description'];
			    $job->save();
			    $messageResponse = 'Job Saved Successfully';
			} catch (NestedValidationException $e) {
				$messageResponse = $e->getMessage();
			}
		}

		return $this->renderHTML('addJob.twig', [
			'messageResponse' => $messageResponse,
		]);
	}
}
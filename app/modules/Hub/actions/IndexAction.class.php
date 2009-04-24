<?php

class Hub_IndexAction extends PullHubHubBaseAction
{

	public function executeRead(AgaviRequestDataHolder $rd)
	{
		/**
		 * @var Hub_GithubModel
		 */
		$model = $this->context->getModel('Package', 'Hub');

		//try {
			$repos = $model->getRepos($rd->getParameter('user'));
		//} catch(Exception $e) {
		//	return $this->handleError($rd);
		//}

		$this->setAttribute('repos', $repos);

		return 'Success';
	}

	/**
	 * Returns the default view if the action does not serve the request
	 * method used.
	 *
	 * @return     mixed <ul>
	 *                     <li>A string containing the view name associated
	 *                     with this action; or</li>
	 *                     <li>An array with two indices: the parent module
	 *                     of the view to be executed and the view to be
	 *                     executed.</li>
	 *                   </ul>
	 */
	public function getDefaultViewName()
	{
		return 'Success';
	}
}

?>
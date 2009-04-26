<?php

class Hub_Package_ViewAction extends PullHubHubBaseAction
{

	public function execute(AgaviRequestDataHolder $rd)
	{
		/**
		 * @var Hub_GithubModel
		 */
		$model = $this->context->getModel('Package', 'Hub');

		try {
			$repo = $model->getRepo($rd->getParameter('user'), $rd->getParameter('repo'));
		} catch (Exception $e) {
			throw $e;
			return $this->handleError($rd);
		}

		if (!$repo) {
			return $this->handleError($rd);
		}

		$this->setAttribute('repo', $repo);

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
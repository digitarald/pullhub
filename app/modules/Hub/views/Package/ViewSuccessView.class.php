<?php

class Hub_Package_ViewSuccessView extends PullHubHubBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$repo = $this->getAttribute('repo');

		$this->setAttribute('_title', $repo['owner'] . '’ ' . $repo['name']);
	}
}

?>
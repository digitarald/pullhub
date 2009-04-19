<?php

class Hub_Package_PullErrorView extends PullHubHubBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$this->setAttribute('_title', 'Package.Pull');
	}
}

?>
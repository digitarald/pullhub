<?php

class Hub_Package_PullSuccessView extends PullHubHubBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$this->setAttribute('_title', 'Package.Pull');
	}
}

?>
<?php

class Hub_Package_ViewSuccessView extends PullHubHubBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$this->setAttribute('_title', 'Package.View');
	}
}

?>
<?php

class Hub_IndexErrorView extends PullHubHubBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$this->setAttribute('_title', 'Index');
	}
}

?>
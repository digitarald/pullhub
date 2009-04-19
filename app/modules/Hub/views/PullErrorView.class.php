<?php

class Hub_PullErrorView extends PullHubHubBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);

		$this->setAttribute('_title', 'Pull');
	}
}

?>